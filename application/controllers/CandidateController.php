<?php

class CandidateController extends Zend_Controller_Action {
	
	public function init() {	
		
	}

	public function indexAction() {
		
		$this->view->title = 'Seznam kandidátů';

	}

	public function editAction() {
		
		$this->view->title = 'Editace kandidáta';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}

	public function detailAction() {
		
		$this->view->title = 'Detail kandidáta';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}
	
}

?>