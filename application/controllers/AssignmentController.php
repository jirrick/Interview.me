<?php

class AssignmentController extends My_Controller_Action {
	
	public function init()
	{
        $user = $this->getUser();
		if ($user !== NULL) {
			$this->view->user = $user;
        	$avatar = $user->getFoto();
			if ($avatar !== NULL) {
				$base64 = base64_encode($avatar->getfoto());
				$this->view->avatarBase64 = $base64;
			}
        }
        else {
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
	// parametr checkSubmitted urcuje, zda se ma konrolovat predchozi odeslani testu 
	private function verifyLink($link, $checkSubmitted){
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
		if ($checkSubmitted && ($assignment->getotevren() == false)) {
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
		$assignment = $this->verifyLink($link, TRUE);	
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
			
			//nastavi jiny view
			$this->_helper->viewRenderer('fill-external');
			
			//vyplnit test
			$this->view->title = 'Test in progress';
			$test = My_Model::get('Tests')->getById($assignment->getid_test());
			$this->view->test = $test;
			$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
			$this->view->candidate = $candidate->getFullName();
			
			//ulozi timestamp zahajeni testu
			date_default_timezone_set('Europe/Prague');
			$now = date("Y-n-j H:i:s");
			$assignment->setdatum_zahajeni($now);
			$assignment->save();
				
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
			$assignment = $this->verifyLink($link, TRUE);
			
			$test = My_Model::get('Tests')->getById($assignment->getid_test());
			$form = new TestFillForm(array('testId' => $test->getid_test(),));	
		
			if ($form->isValid($_POST)) {
				// formular validni
				$this->_helper->viewRenderer('index-external');
				$values = $form->getValues();
			
				// projit jednotlive otazky a odpovedi ulozit odpoved do db	
				foreach($values as $que_id => $que_val){
					// nacte predpis otazky 
					$question = My_Model::get('Questions')->getById($que_id);
					$options = $question->getOptions();   
                    
                    if (count($options) == 0) {
                        //Otevreny text
                        $resp = My_Model::get('Responses')->createRow();
                        $resp->setid_prirazeny_test($assignment->getid_prirazeny_test());
                        $resp->setslovni_odpoved($que_val);
                        $resp->setid_otazka($que_id);
                        $resp->setvyplneno(TRUE);
                        $resp->save();
                    } else  {
                        //ABCD otazka				
                        foreach($options as $opt){
                            $opt_id = $opt->getid_moznost(); // id moznosti
                            $opt_vyplneno = in_array($opt_id, $que_val); // byla moznost zatrzena uzivatelem
                            $resp = My_Model::get('Responses')->createRow();
                            $resp->setid_prirazeny_test($assignment->getid_prirazeny_test());
                            $resp->setid_otazka($que_id);
                            $resp->setid_moznost($opt_id);
                            $resp->setvyplneno($opt_vyplneno);
                            $resp->setspravne($opt_vyplneno == $opt->getspravnost());
                            $resp->save();
                        }
                    }
				}
							
				// ulozeni data odevzdani, odkaz pro kontrolu
				date_default_timezone_set('Europe/Prague');
				$now = date("Y-n-j H:i:s");
				$assignment->setdatum_vyplneni($now);
                $assignment->setkontrola(str_shuffle($assignment->getodkaz()));	
				
				// zneplatnit odkaz a upravit status
				$assignment->setotevren(false);		
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
	
	// obrazovka vyhodnoceni (prehodnoceni) testu
	public function evaluateAction() {
		// kontrola, zda existuje prirazeny test, netestuje se vyplneni (byl uz vyplnen)
		$link = $this->getParam('link');
		$assignment = $this->verifyLink($link, FALSE);
        
        $form = new TestCommentForm();
		$assignmentData = $assignment->get_data();
        $form->setDefaults($assignmentData);
        $this->view->form = $form;
		
        //pro kandidata nastavi jiny view
		$auth = Zend_Auth::getInstance();
		if ($auth->hasIdentity()) {
			$this->_helper->viewRenderer('evaluate-internal');
        } else {
			$this->_helper->viewRenderer('evaluate-external');
		}
        
		$this->view->messages = $this->_helper->flashMessenger->getMessages();
		
		$this->view->title = 'Evaluation of submitted test';
		$this->view->assignment = $assignment;
		$this->view->test = My_Model::get('Tests')->getById($assignment->getid_test());
		$this->view->status = My_Model::get('Statuses')->getById($assignment->getid_status());
		$candidate = My_Model::get('Candidates')->getById($assignment->getid_kandidat());
		$this->view->candidate= $candidate->getFullName();
        
        // ########################### POST ###########################
        // Handles form submission
        if ($this->_request->isPost()) {
            if($this->_request->isXmlHttpRequest()){
                //ajax call request
                $this->_helper->layout->disableLayout();
                $this->_helper->viewRenderer->setNoRender(TRUE);
                
                $postData = $this->getRequest()->getPost();
                $question_id = 0;
                $isCorrect = null;
                if (array_key_exists('question_id', $postData)) $question_id = intval($postData['question_id']);
                if (array_key_exists('isCorrect', $postData)) $isCorrect = filter_var ($postData['isCorrect'], FILTER_VALIDATE_BOOLEAN);
                
                //check request
                if ($question_id> 0 & is_bool($isCorrect)) {
                    // if valid reevaluate
                    $responses = $assignment->getResponses(); 
                    foreach($responses as $r){
                        if ($r->getid_otazka() == $question_id ){
                            $r->setspravne($isCorrect);
                            $r->save();
                        }
                    }
                } else {
                    //deny response when invalid
                    $this->_response->clearBody();
                    $this->_response->clearHeaders();
                    $this->_response->setHttpResponseCode(403);
                }
            } else {
                //form request - Save deetails
                if ($form->isValid($this->_request->getPost())) {
                    $formValues = $form->getValues();
                    // Updates test object in DB
                    $assignment->setkomentar($formValues["komentar"]);
                    
                    // prepocitani spravnych odpovedi - pocita skore pro jednotlive odpovedi, potom se udela prumer
                    // je to takova haluz, protoze odpovedi nejsou v db rozdelene po otazkach
                    $last_question_id = 0;
                    $question_options = 1; //zerodivision hack
                    $question_options_correct = 0;
                    $question_score = 0;
                    $questions_count = 0;
                    $total_score = 0;
                    
                    $responses = $assignment->getResponses(); 
                    foreach($responses as $r){
                        //reset count on new question
                        if ($r->getid_otazka() != $last_question_id) {
                            $questions_count++; 
                            $question_score = (int) round(($question_options_correct / $question_options) * 100);
                            $total_score += $question_score;
                            $question_options = 0;
                            $question_options_correct = 0;
                        }
                        // count correct answers
                        $question_options++;
                        $last_question_id = $r->getid_otazka();
                        if (filter_var ($r->getspravne(), FILTER_VALIDATE_BOOLEAN)) $question_options_correct++;   
                    }
                    //include last question
                    $question_score = (int) round(($question_options_correct / $question_options) * 100);
                    $total_score += $question_score;
                    
                    $result = (int) round($total_score/ $questions_count);
                    $assignment->sethodnoceni($result);	
                    $assignment->save();	
                                
                }
            }
        }
	
	}
}