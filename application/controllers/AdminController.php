<?php

class AdminController extends My_Controller_Action {
    
    public function init() {
        
        // nastaveni layoutu pro administraci
        $adminActions = array('login', 'registration');
        $actionName = $this->getRequest()->getActionName();
    
        if ($actionName && in_array($actionName, $adminActions)) {
            $this->_helper->layout->setLayout('admin');
        }
        
    }

    public function registrationAction() {
        // Only for administrators
        if (!$this->getUser() || !$this->getUser()->isAdmin()) {
            // Redirects
            $this->_helper->redirector->gotoRoute(array('controller' => 'admin', 'action' => 'login'), 'default', true);
            return;
        }

        $this->view->title = 'Registrace administrátora';
    
        $form = new RegistrationForm();
        
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $formValues = $form->getValues();                                               
                
                if( $formValues["heslo"] != $formValues["heslo2"]){
                  
                    //todo barevne zvyrazeni chybnych poli + kontrola formatu
                    //neni stejne heslo
                   
                }else{
                    
                    
                    //pokud už uživatel není v databázi
                    
                    $formValues["heslo"] = sha1("interview".$formValues["heslo"]);
                    $user = My_Model::get('Users')->createRow();
                    $user->updateFromArray($formValues);

                    $this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
                                    'action' => 'index'),
                                    'default',
                                    true);   
                    
                }
            }
        }

        
        
        $this->view->registrationform = $form;
    }
    
    public function loginAction() {
        $this->view->title = 'Přihlášení administrátora';
        
        // flash messenger
        if ($this->_helper->FlashMessenger->hasMessages()) {
            $this->view->messages = $this->_helper->FlashMessenger->getMessages();
        }
        
        $form = new LoginForm();         
        $this->view->loginform = $form;      
    }
    
    public function logoutAction() {
        Zend_Auth::getInstance()->clearIdentity();
               
        $this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
					'action' => 'index'),
					'default',
					true);
    } 
}

?>