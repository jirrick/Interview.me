<?php

class QuestionController extends Zend_Controller_Action {
	
	public function init() {	
		
	}

	public function indexAction() {
		
		$this->view->title = 'Questions';

	}

	public function editAction() {
		
		$this->view->title = 'Add new Question';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}
	
}

?>