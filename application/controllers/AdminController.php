<?php

class AdminController extends Zend_Controller_Action {
    
    public function registrationAction() {
        $this->view->title = 'Registrace administrátora';
    
        $form = new RegistrationForm();
        
        if ($this->_request->isPost()) {
            if ($form->isValid($this->_request->getPost())) {

                $formValues = $form->getValues();
                                               
                $formValues["heslo"] = sha1("interview".$formValues["heslo"]);

                
                $user = My_Model::get('Users')->createRow();
                $user->updateFromArray($formValues);
	
                $this->_helper->flashMessenger->addMessage("Registrace proběhla úspěšně.");

                $this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
                                'action' => 'index'),
                                'default',
                                true);
            }
        }
        
        
        $this->view->registrationform = $form;
    }
    
    public function loginAction() {
        $this->view->title = 'Přihlášení administrátora';
        
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

    public function errorAction(){
        
    }
    
}

?>