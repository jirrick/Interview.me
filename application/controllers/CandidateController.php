<?php

class CandidateController extends My_Controller_Action {
	

	public function init()
	{
		$this->view->user = $this->getUser();
    }

	public function indexAction() 
	{
		$candidates = My_Model::get('Candidates')->fetchAll();
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
		
		$this->view->candidates = $candidates;
		$this->view->title = 'Candidates';
	}


	public function editAction()
	{
		$candidateId = $this->_request->getParam('id');

		// Editace kandidáta
		if (!empty($candidateId)) {
			$this->view->title = 'Edit Candidate';
			$record = My_Model::get('Candidates')->getById($candidateId);
		}
		// Vytvoření nového
		else {
			$this->view->title = 'Create new Candidate';

		}

	}

	public function detailAction()
	{
		$this->view->title = 'Detail of Candidate';
		$this->view->messages = $this->_helper->flashMessenger->getMessages();

		$candidateId = $this->_request->getParam('id');
		if (!empty($candidateId)) {
			$candidate = My_Model::get('Candidates')->getById($candidateId);
		}

		if ($candidate === null) {
			$this->_helper->flashMessenger->addMessage("Kandidát nebyl nalezen");
			$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
														'action' => 'index'),
														'default',
														true);
		}
		else {
			$this->view->candidate = $candidate;

			$avatar = $candidate->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$this->view->candidateAvatarBase64 = $base64;
			}
						
		}
	}
	
}

?>