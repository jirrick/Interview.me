<?php

class QuestionContent {
    public $question;
    public $options;
    public $order;
}

class TestController extends My_Controller_Action {

    public function init() {
        $user = $this->getUser();
        if ($user !== NULL) {
            $this->view->user = $user;
            $avatar = $user->getFoto();
            if ($avatar !== NULL) {
                $base64 = base64_encode($avatar->getfoto());
                $this->view->avatarBase64 = $base64;
            }
        }
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


    public function editAction() 
    {
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        
        $testId = $this->_request->getParam('id');


        // Creates form instance
        $testFrom = new TestForm();
        $this->view->testForm = $testFrom;      

        // Loads data from database
        $technologies = My_Model::get('Technologies')->fetchAll();
        $seniorities = My_Model::get('Seniorities')->fetchAll();

        // Fills form selects
        $testFrom->getElement('id_technologie')->setMultiOptions($this->transformTechnologies($technologies));
        $testFrom->getElement('id_seniorita')->setMultiOptions($this->transformSeniorities($seniorities));

        // Edit test page
        if (!empty($testId)) {
            $this->view->testId = $testId;
            $this->view->title = 'Edit Test';

            $test = My_Model::get('Tests')->getById($testId);

            $testData = $test->get_data();
            $testFrom->setDefaults($testData);

            // Loads questions with options
            $questions = $test->getQuestions();

            $questionForms = array();
            foreach ($questions as $q) {
                $questionForm = new QuestionForm(array('questionId' => $q->getid_otazka(),));
                $questionForm->setName('q' . strval($q->getid_otazka()));
                $questionForm->setAction($this->view->url(array('controller' => 'test', 'action' => 'save-question', 'testId' => $testId, 'questionId' => $q->getid_otazka()), 'default', true));
                $questionForms[] = $questionForm;
            }

            $newQuestionForm = new QuestionForm(array('count' => 3,));;
            $newQuestionForm->setName('new');
            $newQuestionForm->setAction($this->view->url(array('controller' => 'test', 'action' => 'save-question', 'testId' => $testId), 'default', true));

            $questionForms[] = $newQuestionForm;
            $this->view->questionForms = $questionForms;
        }
        // Create test page
        else {
            $this->view->title = 'Add new Test';
        }

        // ########################### POST ###########################
        // Handles form submission
        if ($this->_request->isPost()) {
            if ($testFrom->isValid($this->_request->getPost())) {
                $formValues = $testFrom->getValues();

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
                $this->_helper->redirector->gotoRoute(array('controller' => 'test', 'action' => 'edit', 'id' => $test->id_test ), 'default', true);
            }
        }
    }

    public function resultAction() {        
        $this->view->title = 'Test results';
    }

    public function saveQuestionAction() 
    {
        // Disables view script call
        $this->_helper->viewRenderer->setNoRender(true);

        // Disables layout to action mapping
        $this->_helper->layout->disableLayout();

        if ($this->_request->isPost()) {
            // load params
            $testId = $this->getParam('testId');
            $questionId = $this->getParam('questionId');
            $test = My_Model::get('Tests')->getById($testId);
            
            $rawcount = intval($this->getParam('count'));
            $count = (($rawcount > -1 && $rawcount  <=6) ? $rawcount : 3);
            $form = new QuestionForm(array('count' => $count));  

            if ($form->isValid($this->_request->getPost())) {

                $formValues = $form->getValues();
                Zend_Debug::dump($formValues);

                //check if at least one option is checked
                if($this->checkAllFalse($formValues)) {
                    $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                    $flash->clearMessages();
                    $flash->addMessage('At least one option have to be right.');
                    Zend_Debug::dump('at least one has to be right');

                    // redirect to test edit page
                    $this->_helper->redirector->gotoRoute(array('controller' => 'test', 'action' => 'edit', 'id' => $testId ), 'default', true);
                    return;
                }

                // ### SAVE/UPDATE QUESTION ###
                $oldQuestion = null;
                $question = null;
                $isNewQuestion = true;

                if ($questionId === NULL) {
                    //create question
                    $question = My_Model::get('Questions')->createRow();

                    Zend_Debug::dump('no question id');
                    Zend_Debug::dump('Create question');
                }
                else {
                    // load existing
                    $oldQuestion = My_Model::get('Questions')->getById($questionId);
                                       
                    if ($oldQuestion === NULL ||
                             ($oldQuestion->isAnswered()
                              && (strcmp($formValues['otazka'], $oldQuestion->getobsah()) !== 0
                                    || strcmp($formValues['language'], strval($oldQuestion->getid_jazyk())) !== 0 ))) {
                        Zend_Debug::dump('no old question || (is anwered && changed question)');
                        Zend_Debug::dump('Create question');
                        $question = My_Model::get('Questions')->createRow();
                    }
                    else {
                        $question = $oldQuestion;
                        $isNewQuestion = false;
                        Zend_Debug::dump('use old question');
                    }
                }

                // update question with new data
                $questionValues = array(
                    "id_test"  => $testId,
                    "obsah" =>  $formValues['otazka'],
                    "id_jazyk" => (strcmp($formValues['language'], '0') != 0 ? $formValues['language'] : null)
                );
                $question->updateFromArray($questionValues);

                // archive old if exist
                if ($oldQuestion !== NULL && $isNewQuestion) {
                    Zend_Debug::dump('archive old question');
                    $oldQuestion->id_test = NULL;
                    $oldQuestion->revize = $question->getid_otazka();
                    $oldQuestion->save();
                }

                // save question id
                $questionId = $question->id_otazka;

                // ### SAVE/UPDATE OPTIONS ###
                $optionContents = $this->loadOptionsFromFormValues($formValues, $questionId);
                if ($oldQuestion !== NULL) {
                    $existingOptions = $oldQuestion->getOptions();
                }
                            
                // question is new or existing options are empty
                if ($isNewQuestion || empty($existingOptions)) {
                    Zend_Debug::dump('is new question || no existing options');
                    // create options with given content
                    foreach ($optionContents as $content) {
                        Zend_Debug::dump('create new option');
                        $option = My_Model::get('Options')->createRow();
                        $option->updateFromArray($content);
                    }
                }
                else {
                    Zend_Debug::dump('!(is new question || no existing options)');                 
                    
                    $newcount = count($optionContents);
                    $oldcount = count($existingOptions);
                    
                    // update existing options with given content
                    for ($i = 0 ; $i < $newcount ; $i++) {
                        
                        if ($i < $oldcount) {
                            // pokud je novy pocet otazek mensi nez puvodni, tak se porovnavaji se starymi moznostmi                        
                            // pokud nebyla moznost zmenena, jde se na dalsi
                            if (strcmp($optionContents[$i]["obsah"], $existingOptions[$i]->obsah) == 0
                                    && $optionContents[$i]["spravnost"] === $existingOptions[$i]->spravnost){
                                Zend_Debug::dump('no change');
                                continue; 
                            }
                            
                            $oldOption = $existingOptions[$i];
                            $option;
                            $isNewOption = true;

                            if ($oldOption === NULL || $oldOption->isAnswered()) {
                                Zend_Debug::dump('no old option || (is answered && changed)');
                                Zend_Debug::dump('create new option');
                                $option = My_Model::get('Options')->createRow();
                            }
                            else {
                                Zend_Debug::dump('not answered, use old option');
                                $option = $oldOption;
                                $isNewOption = false;
                            }

                            $option->updateFromArray($optionContents[$i]);

                            // archive old if exist
                            if ($oldOption !== NULL && $isNewOption) {
                                Zend_Debug::dump('archive old option');
                                $oldOption->id_otazka = NULL;
                                $oldOption->revize = $option->getid_moznost();
                                $oldOption->save();
                            }
                        } else {
                            // pokud je moznosti vic, tak se pridavaji rovnou dalsi
                            Zend_Debug::dump('add new option');
                            $option = My_Model::get('Options')->createRow();
                            $option->updateFromArray($optionContents[$i]);
                        }
                    }
                    
                    // archive removed options
                    for ($i = $newcount ; $i < $oldcount ; $i++) {
                        Zend_Debug::dump('archive unused options');
                        $oldOption = $existingOptions[$i];
                        $oldOption->id_otazka = NULL;
                        $oldOption->save();  
                    }
                }             
            }
            else {
                Zend_Debug::dump($form->getMessages());
                Zend_Debug::dump('Invalid');

                $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                $flash->clearMessages();
                $flash->addMessage('Question form is not valid.');
            }
        }

        // redirect to test edit page
        $this->_helper->redirector->gotoRoute(array('controller' => 'test', 'action' => 'edit', 'id' => $testId ), 'default', true);
    }
    
    public function addfieldAction() {     
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        
        $postData = $this->getRequest()->getPost();
        $id = 0;
        if (array_key_exists('count', $postData)) $id = intval($postData['count']) + 1;
        
        if ($id > 0 && $id <=6) {
            $optionsNames = array('', 'A', 'B', 'C', 'D', 'E', 'F');
            $form = new QuestionForm(); 
            $odpoved = $form->createElement('text', 'odpoved' . strval($id), array(
                            'placeholder' => $optionsNames[$id],
                            'class' => 'input dd-test',
                            'required' => true,
                            'label' => false,
                            'filters' => array('StringTrim')
                            ));
                        
            $check = $form->createElement('checkbox', 'check' . strval($id), array(
                            'class' => 'dd-chc',
                            'disableHidden' => true
                            ));
            echo $odpoved->__toString();
            echo $check->__toString();
        } else {
            $this->_response->clearBody();
            $this->_response->clearHeaders();
            $this->_response->setHttpResponseCode(403);
        }
    }


    private function loadOptionsFromFormValues($formValues, $questionId)
    {     
        $content = array();
        
        $question = array();
        foreach($formValues as $key => $val){
            //musi byt v poradi jako ve formulari (hlavne posledni akce, ktera uzavira element)!!!
            //odpoved
            if (stripos($key, 'odpoved') !== FALSE){
                $option['obsah'] = $val;
                $option['id_otazka'] = $questionId;
            }
            //check
            if (stripos($key, 'check') !== FALSE){
                $option['spravnost'] = $val;
                $content[] = $option;
                $option = array();
            }
        }
        return $content;
    }
    
    private function checkAllFalse($formValues)
    {
       $allfalse = TRUE;
       foreach($formValues as $key => $val){   
            //check
            if (stripos($key, 'check') !== FALSE){
                if ($val == TRUE) {
                    $allfalse = FALSE;
                };
            }
        }
       return $allfalse;
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