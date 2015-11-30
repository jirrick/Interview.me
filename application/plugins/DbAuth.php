<?php

/**
 * Plugin zajistuje autentifikaci uzivatele a presmerovani
 * Nastaveni je prebrano z application.ini s prefixem auth
 *
 * @see Zend_Auth_Adapter_DbTable
 */
class Application_Plugin_DbAuth extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @var array
     */
    private $_options;

    /**
     * Metoda vrati konkretni hodnotu z konfigurace
     * Pokud klic neni nalezen, vyhodime vyjimku
     *
     * @param string $key
     * @return mixed
     */
    private function _getParam($key)
    {
        if (is_null($this->_options))
        {
            $this->_options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getOptions();
        }

        if (!array_key_exists($key, $this->_options['auth']))
        {
            throw new Zend_Controller_Exception("Param {auth.$key} not found in application.ini");
        }
        else
        {
            return $this->_options['auth'][$key];
        }
    }

    /**
     * Wrapper nad metodou _getParam
     * Umozni nam pristupovat ke konfiguraci primo pres $this
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->_getParam($key);
    }

    /**
     * Enter description here...
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        
        
        // ziskame instanci redirector helperu, ktery ma starosti presmerovani
        $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');

        $auth = Zend_Auth::getInstance();
        // Stav o autentifikaci uzivatele (prihlaseni) se musi nekde udrzovat, vychozi zpusob je session
        // u session lze nastavit namespace, vychozi je Zend_Auth
        //$auth->setStorage(new Zend_Auth_Storage_Session('My_Auth'));

        if ($request->getParam('logout'))
        {
            // detekovano odhlaseni

            $auth->clearIdentity();

            // kvuli bezpecnosti provedeme presmerovani
            $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController);
        }

        if ($request->getPost('login'))
        {

            $db = Zend_Db_Table::getDefaultAdapter();


            // Vytvarime instance adapteru pro autentifikaci
            // nastavime parametry podle naseho nazvu tabulky a sloupcu
            // treatment obsahuje pripadne pouzitou hashovaci funkci pro heslo, napr. SHA1

            $adapter = new Zend_Auth_Adapter_DbTable($db, $this->tableName, $this->identityColumn, $this->credentialColumn, $this->treatment);


            $form = new LoginForm();

            // validace se nezdari, napr. prazdny formular
            if (!$form->isValid($request->getPost()))
            {
                // FlashMessenger slouzi k uchovani zprav v session

                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flash->clearMessages();

                $flash->addMessage('Please fill the login form');


                $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
            }

            $username = $form->getValue($this->loginField);
            $password = $form->getValue($this->passwordField);

            // přidáme salt
            $password = "interview".$password;
            
            // jmeno a heslo predame adapteru
            $adapter->setIdentity($username);
            $adapter->setCredential($password);

            // obecny proces autentifikace s libovolnym adapterem
            $result = $auth->authenticate($adapter);

            if ($auth->hasIdentity())
            {
                // Uzivatel byl uspesne overen a je prihlasen
                // identity obsahuje v nasem pripade ID uzivatele z databaze
                
                $identity = $auth->getIdentity();
            
                // presmerujeme
                $redirector->gotoSimpleAndExit($this->successAction, $this->successController);
            }
            else
            {
                // autentifikace byla neuspesna
                // FlashMessenger slouzi k uchovani zprav v session
                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                
                // vlozime do session rovnou chybove hlasky, ktere pak predame do view
                foreach ($result->getMessages() as $msg)
                {
                    $flash->addMessage($msg);
                }

                $redirector->gotoSimpleAndExit($this->failedAction, $this->failedController, null, array('login-failed' => 1));
            }
        }
    }
}