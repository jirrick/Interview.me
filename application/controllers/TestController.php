<?php

class TestController extends My_Controller_Action {
	
	public function init() {
  		$this->view->user = $this->getUser();
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