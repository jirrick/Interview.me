<?php

class QuestionContent {
    public $question;
    public $options;
    public $order;
}

class TestController extends My_Controller_Action {
	
	public function init() {
  		$this->view->user = $this->getUser();
    }

	public function indexAction() {
		$tests = My_Model::get('Tests')->fetchAll();
		
		$this->view->tests = $tests;
		$this->view->title = 'Tests';
	}

    public function detailAction()
    {
        $testId = $this->_request->getParam('id');
        $test = My_Model::get('Tests')->getById($testId);
        $test->updateQuestionsCount();
        $this->customizeView($test, $this->view);
    }

    private function customizeView($test, $view)
    {
        // Loads objects from database
        $creator = $test->getCreator();
        $technology = $test->getTechnology();
        $seniority = $test->getSeniority();

        $view->title = 'Test detail';
        $view->test = $test;
        $view->technologyName = $technology->getnazev();
        $view->seniorityName = $seniority->getnazev();

        // Creator name
        if (strlen($creator->getjmeno()) > 0 && strlen($creator->getprijmeni()) > 0) {
            $view->creatorName = $creator->getjmeno() . " " . $creator->getprijmeni();
        }
        else if (strlen($creator->getjmeno()) > 0) {
            $view->creatorName = $creator->getjmeno();
        }
        else if (strlen($creator->getprijmeni()) > 0) {
            $view->creatorName = $creator->getprijmeni();
        }
        else {
            $view->creatorName = "–";
        }

        // Load questions and it's options
        $qTable = My_Model::get('Questions');
        $oTable = My_Model::get('Options');

        $questions = $qTable->fetchAll($qTable->select()->where('id_test = ?', $test->getid_test()));
        
        $qContents = array();
        for ($i = 0 ; $i < count($questions) ; $i++) {
            $qContent = new QuestionContent();
            $qContent->order = $i + 1;
            $qContent->question = $questions[$i];
            $qContent->options = $oTable->fetchAll($oTable->select()->where('id_otazka = ?', $questions[$i]->getid_otazka()));

            $qContents[$i] = $qContent;
        }

        $view->questions = $qContents;
    }


	public function editAction() {
             
            $testId = $this->_request->getParam('id');
            
            // Creates form instance
            $form = new TestForm();
            $this->view->testform = $form;      

            // Loads data from database
            $technologies = My_Model::get('Technologies')->fetchAll();
            $seniorities = My_Model::get('Seniorities')->fetchAll();
 
            // Fills form selects
            $form->getElement('id_technologie')->setMultiOptions($this->transformTechnologies($technologies));
            $form->getElement('id_seniorita')->setMultiOptions($this->transformSeniorities($seniorities));
           
            // Edit test page
            if (!empty($testId)) {
               $this->view->title = 'Edit Test';
               
               $test = My_Model::get('Tests')->getById($testId);
               
               $testData = $test->get_data();
               $form->setDefaults($testData); 
            }
            // Create test page
            else {
                $this->view->title = 'Add new Test';
            }
            
            // ########################### POST ###########################
            // Handles form submission
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $formValues = $form->getValues();

                    $test;
                    // Editing existing test 
                    if (!empty($testId)) {
                        $test = My_Model::get('Tests')->getById($testId);
                    }
                    // Creates new test
                    else {
                        date_default_timezone_set('UTC');
                        $formValues['datum_vytvoreni'] = date("Y-n-j");
                        $formValues['id_kdo_vytvoril']  = $this->getUser()->id_uzivatel;
                                              
                        $test = My_Model::get('Tests')->createRow();
                    }
                    
                    // Updates test object in DB
                    $test->updateFromArray($formValues);              
                    $this->_helper->redirector->gotoRoute(array('controller' => 'question', 'action' => 'edit', 'testId' => $test->id_test ), 'default', true);
                }
            }
	}

	public function resultAction() {		
            $this->view->title = 'Test results';
	}

        private function transformTechnologies($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_technologie] = $row->nazev;
		}
		return $rVal;
	}
        
        private function transformSeniorities($arr)
	{
		$rVal = array();
		foreach ($arr as $row) {
			$rVal[$row->id_seniorita] = $row->nazev;
		}
		return $rVal;
	}


}