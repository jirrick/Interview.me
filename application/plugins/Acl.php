<?php

/**
 * Plugin resi autorizaci uzivatele
 * Pokud uzivatel nema pristup do vybraneho controlleru/akce
 * je presmerovan na prihlasovaci obrazovku
 * do messengeru je pridana chybova hlaska
 */
class Application_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     *
     * @param Zend_Controller_Request_Abstract $request
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        $options = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getApplication()->getOptions();

        $config = new Zend_Config($options);

        $acl = new My_Acl($config);

        $role = 'guest';

        if (Zend_Auth::getInstance()->hasIdentity())
        {
            $role = 'user';   
            
            if(Zend_Auth::getInstance()->hasIdentity()){
                return; 
            }else{
               
                 $login = Zend_Auth::getInstance()->getIdentity();
                 $user = My_Model::get('Users')->getUserByEmail($login);
                 
                 if($user->admin == 1){
                    $role = 'admin';   
                 }   
            }
        }

        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $resource = $controller;
        $privilege = $action;

        if (!$acl->has($resource))
        {
            $resource = null;
        }

        if (is_null($privilege))
        {
            $privilege = 'index';
        }

        if (!$acl->isAllowed($role, $resource, $privilege))
        {

            $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
            $flash->addMessage('Access Denied');

            $redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('Redirector');
            $redirector->gotoSimpleAndExit('login', 'admin');
        }
    }
}