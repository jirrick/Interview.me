<?php

class AssignmentController extends My_Controller_Action {
	
	public function init()
	{
        $this->view->user = $this->getUser();
		
		if ($this->getUser() === null) {
            $this->_helper->layout->setLayout('basic');
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
		$this->view->title = '';
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
			$this->_helper->flashMessenger->addMessage("ERROR: Invalid action.");
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
	
	
	//zobrazeni linku s testem
	public function testAction() {
		// kontrola, zda existuje prirazeny test
		$link = $this->getParam('link');
		$assignment = $this->verifyLink($link);	
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
			
		if ($this->_request->isPost()) {
			// ---- POST - zobrazeni testu k vyplneni -------------------------------------------------------------
			//kontrola eula
			$eula = $this->getParam('eula');
			if ($eula != 'agree') {
				$this->_helper->flashMessenger->addMessage("ERROR: You must agree with agreements.");
				$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
															'action' => 'test',
															'link' => $link),
															'default',
															true);
			}
			
			//pro kandidata nastavi jiny view
			$auth = Zend_Auth::getInstance();
			if (!$auth->hasIdentity()) {
				$this->_helper->viewRenderer('fill-external');
			} else {
				$this->_helper->viewRenderer('fill-internal');
			}	
			
			//vyplnit test
			$this->view->title = 'Test in progress';
			$test = My_Model::get('Tests')->getById($assignment->getid_test());
			$this->view->test = $test;
			$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
			$this->view->candidate = $candidate->getFullName();
			// Creates form instance
			$form = new TestFillForm(array('testId' => $test->getid_test(),));
			$form->setAction($this->view->url(array('controller' => 'assignment', 'action' => 'submit', 'link' => $link), 'default', true));
			$this->view->form = $form;
	
		} else {
			// ---- GET - zobrazeni prehledu a eula --------------------------------------------------------------
			//pro kandidata nastavi jiny view
			$auth = Zend_Auth::getInstance();
			if (!$auth->hasIdentity()) {
				$this->_helper->viewRenderer('test-external');
			} else {
				$this->_helper->viewRenderer('test-internal');
			}
			
			$this->view->title = 'Assigned test';
			$this->view->test = My_Model::get('Tests')->getById($assignment->getid_test());
			$this->view->status = My_Model::get('Statuses')->getById($assignment->getid_status());
			$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
			$this->view->candidate= $candidate->getFullName();

		}	
	}
	
	// odevzdani testi
	public function submitAction() {
		if ($this->_request->isPost()) {
			// kontrola, zda existuje prirazeny test
			$link = $this->getParam('link');
			$assignment = $this->verifyLink($link);
			
			$test = My_Model::get('Tests')->getById($assignment->getid_test());
			$form = new TestFillForm(array('testId' => $test->getid_test(),));	
		
			if ($form->isValid($_POST)) {
				// formular validni
				$this->_helper->viewRenderer('index-external');
				$values = $form->getValues();
				
				// pocitani spravnych
				$count_all = 0;
				$count_correct = 0;
				
				// projit jednotlive otazky a odpovedi ulozit odpoved do db	
				foreach($values as $que_id => $que_val){
					// nacte predpis otazky 
					$question = My_Model::get('Questions')->getById($que_id);
					$options = $question->getOptions();
					
					foreach($options as $opt){
						$opt_id = $opt->getid_moznost(); // id moznosti
						$opt_vyplneno = in_array($opt_id, $que_val); // byla moznost zatrzena uzivatelem
						$opt_spravne = ($opt_vyplneno == $opt->getspravnost()); // zadal uzivatel spravnou odpoved
						$count_all++;
						if ($opt_spravne) $count_correct++;
						
						$resp = My_Model::get('Responses')->createRow();
						$resp->setid_prirazeny_test($assignment->getid_prirazeny_test());
						$resp->setid_otazka($que_id);
						$resp->setid_moznost($opt_id);
						$resp->setvyplneno($opt_vyplneno);
						$resp->setspravne($opt_spravne);
						$resp->save();
					}
				}
				
				$result = (int) round(($count_correct / $count_all) * 100);
				
				// zneplatnit odkaz a upravit status
				$assignment->setotevren(false);
				$assignment->sethodnoceni($result);		
				$statuses = new Statuses();
				$statusID = $statuses->getStatusID('SUBMITTED');
				$assignment -> setid_status($statusID);
				$assignment -> save();
				
				$this->_helper->flashMessenger->addMessage("Test has been successfully submitted.");
				$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
															'action' => 'index'),
															'default',
															true);
				
			} else {
				// nevalidni
				$this->_helper->flashMessenger->addMessage("ERROR: Submission not valid.");
			    $this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'test',
														'link' => $link),
														'default',
														true);
			}

		} else {
			//neni post
			$this->_helper->flashMessenger->addMessage("ERROR: Invalid action.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
		}
	}
	
	// zobrazeni detailu testu
	public function detailAction() {
		// kontrola, zda existuje prirazeny test
		$link = $this->getParam('link');
		if (!empty($link)) {
			$assignments = new Assignments();
			$assignment = $assignments->getFromLink($link);
			
		}
		if ($assignment === null) {
			$this->_helper->flashMessenger->addMessage("ERROR: Invalid action.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
		}
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
			
		//pro kandidata nastavi jiny view
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$this->_helper->viewRenderer('detail-internal');
		} else {
			$this->_helper->flashMessenger->addMessage("ERROR: Invalid action.");
			$this->_helper->redirector->gotoRoute(array('controller' => 'assignment',
														'action' => 'index'),
														'default',
														true);
		}
		
		$this->view->title = 'Detail of submitted test';
		$this->view->test = My_Model::get('Tests')->getById($assignment->getid_test());
		$this->view->status = My_Model::get('Statuses')->getById($assignment->getid_status());
		$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
		$this->view->candidate= $candidate->getFullName();
	
	}
}