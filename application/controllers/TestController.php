<?php

class TestController extends My_Controller_Action {
	
	public function init() {
  		$this->view->user = $this->getUser();
    }

	public function indexAction() {
		$tests = My_Model::get('Tests')->fetchAll();
		
		$this->view->tests = $tests;
		$this->view->title = 'Tests';
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
               //TODO edit
            }
            // Create test page
            else {
                $this->view->title = 'Add new Test';
            }
            
            // ########################### POST ###########################
            // Handles form submission
            Zend_Debug::dump("HOVNO 1");
            if ($this->_request->isPost()) {
                Zend_Debug::dump("HOVNO 2");
                if ($form->isValid($this->_request->getPost())) {
                    Zend_Debug::dump("HOVNO 3");
                    $formValues = $form->getValues();

                    $test;
                    // Editing existing test 
                    if (!empty($testId)) {
                        //TODO edit existing test 
                    }
                    // Creates new test
                    else {
                        $test = My_Model::get('Tests')->createRow();
                    }

                    // Updates test object in DB
                    $test->updateFromArray($formValues);

                    $this->_helper->redirector->gotoRoute(array('controller' => 'test', 'action' => 'index'), 'default', true);
                }
            }
            else {
                Zend_Debug::dump("HOVNO ERR");
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

?>