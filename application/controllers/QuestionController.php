<?php

class QuestionController extends Zend_Controller_Action {
	
	public function init() {	
		
	}

	public function indexAction() {
		
		$this->view->title = 'Seznam otázek';

	}

	public function editAction() {
		
		$this->view->title = 'Editace otázky';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}
	
}

?>