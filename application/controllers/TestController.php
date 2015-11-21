<?php

class TestController extends Zend_Controller_Action {
	
	public function init() {
  
            //Zjištění zda je uživatel admin (kvůli viditelnosti registrace)
            $login = Zend_Auth::getInstance()->getIdentity();
            $user = My_Model::get('Users')->getUserByEmail($login);
            $this->view->user = $user;
        }

	public function indexAction() {
		$tests = My_Model::get('Tests')->fetchAll();
		
		$this->view->tests = $tests;
		$this->view->title = 'Tests';
	}


	public function editAction() {
		
		$this->view->title = 'Add new Test';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}

	public function resultAction() {
		
		$this->view->title = 'Test results';

	}
	
}

?>