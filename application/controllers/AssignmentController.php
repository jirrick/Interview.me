<?php

class AssignmentController extends My_Controller_Action {
	
	public function init()
	{
        //pro kandidata nastavi jiny layout
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
            $this->_helper->layout->setLayout('admin');
        }
    }

	public function indexAction() {
		//pro kandidata nastavi jiny view
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_helper->viewRenderer('index-external');
        } else {
			$this->_helper->viewRenderer('index-internal');
		}
		$this->view->title = 'TODO - TEST HOME';
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
	}
	
	//prirazeni testu
	public function assignAction() {
		// akceptuje pouze POST requesty
		if ($this->_request->isPost()) {
			// kontrola, ze existuje kandidat
			$candidateId = $this->getParam('candidate');
			if (!empty($candidateId)) {
				$candidate = My_Model::get('Candidates')->getById($candidateId);
			}
			if ($candidate === null) {
				$this->_helper->flashMessenger->addMessage("Candidate hasn't been found. Test hasn't been assigned.");
				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
															'action' => 'index'),
															'default',
															true);
			}
			
			// kontrola, ze existuje test
			$testId = $this->_request->getParam('test');
			if (!empty($testId)) {
				$test = My_Model::get('Tests')->getById($testId);
			}
			if ($test === null) {
				$this->_helper->flashMessenger->addMessage("Test hasn't been found and couldn't been assigned.");
				$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
														'action' => 'detail',
														'id' => $candidateId),
														'default',
														true);
			}
			
			// kandidat i test existuji -> provest prirazeni
			$assignment = My_Model::get('Assignments')->createRow();
			$assignment -> setid_test($testId);
			$assignment -> setid_kandidat($candidateId);		
			$assignment -> setodkaz(uniqid('', true));
			
			$statuses = new Statuses();
			$statusID = $statuses->getStatusID('ASSIGNED');
			$assignment -> setid_status($statusID);
			
			$assignment -> setotevren(true);
			$assignment -> setdatum_prirazeni(date("Y-n-j"));
			$assignment -> setid_kdo_priradil($this->getUser()->id_uzivatel);
			
			$assignment->save();
			
			
			// SUCCESS
			$this->_helper->flashMessenger->addMessage("Test \"". $test->getnazev() ."\" has been assigned to this candidate.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
														'action' => 'detail',
														'id' => $candidateId),
														'default',
														true);
			
		} else {
			// GET requesty presmeruje na index
			$this->_helper->redirector->gotoRoute(array('controller' => 'index',
					'action' => 'index'),
					'default',
					true);
			
		}
	}
	
	
	// Prevede link na interni objekt prirazenenho testu (pouze ve stavu k vyplneni)
	private function verifyLink($link){
		if (!empty($link)) {
			$assignments = new Assignments();
			$assignment = $assignments->getFromLink($link);
			
		}
		if ($assignment === null) {
			$this->_helper->flashMessenger->addMessage("ERROR: Invalid test link.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
		}
		if ($assignment->getotevren() == false) {
			$this->_helper->flashMessenger->addMessage("ERROR: This test has been already submitted.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
		}
		return $assignment;
	}
	
	
	
	//zobrazeni testu - TODO
	public function testAction() {
		//pro kandidata nastavi jiny view
		$auth = Zend_Auth::getInstance();
		if (!$auth->hasIdentity()) {
			$this->_helper->viewRenderer('test-external');
        } else {
			$this->_helper->viewRenderer('test-internal');
		}
		
		// kontrola, zda existuje prirazeny test
		$link = $this->getParam('link');
		$assignment = $this->verifyLink($link);			
		
		//TODO vyplnit test
		$this->view->title = 'TODO - SUBMIT TEST';
		$test = My_Model::get('Tests')->getById($assignment->getid_test());
		$this->view->test = $test->getnazev();
		$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
		$this->view->candidate= $candidate->getFullName();
	}
	
	// odevzdani testi - TODO
	public function submitAction() {
		// akceptuje pouze POST requesty
		if ($this->_request->isPost()) {
			// kontrola, zda existuje prirazeny test
			$link = $this->getParam('link');
			$assignment = $this->verifyLink($link);
			
			//TODO - vyhodnoceni
			
			
			
			//SUCCESS
			$assignment->setotevren(false);
			
			$statuses = new Statuses();
			$statusID = $statuses->getStatusID('SUBMITTED');
			$assignment -> setid_status($statusID);
			$assignment -> save();
			
			$this->_helper->flashMessenger->addMessage("This test has been successfully submitted.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
			
			
		} 
	}
}