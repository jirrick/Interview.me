<?php

class CandidateController extends Zend_Controller_Action {
	
	public function init() {	
            
	}

	public function indexAction() {		
		$candidates = My_Model::get('Candidates')->fetchAll();
		
		$this->view->candidates = $candidates;
		$this->view->title = 'Candidates';

	}

	public function editAction() {
		
		$this->view->title = 'Add new Candidate';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}

	public function detailAction() {
		
		$this->view->title = 'Detail of Candidate';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}
	
}

?>