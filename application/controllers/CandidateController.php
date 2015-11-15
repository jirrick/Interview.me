<?php

class CandidateController extends Zend_Controller_Action {
	
	public function init() {
            
            //Zjištění zda je uživatel admin (kvůli viditelnosti registrace)
            $login = Zend_Auth::getInstance()->getIdentity();
            $user = My_Model::get('Users')->getUserByEmail($login);
            $this->view->user = $user;
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