<?php

class QuestionController extends My_Controller_Action {
	
	public function init() {	
		
	}

	public function indexAction() {
		
		$this->view->title = 'Questions';

	}
        
        public function deleteAction(){
            if ($this->_request->isPost()) {
                $productId = $this->_getParam('id');

                if (!empty($productId)) {
                        $record = My_Model::get('Products')->getById($productId);

                        if ($record) {
                                $record->delete();
                                $this->_helper->flashMessenger->addMessage("Produkt byl odstraněn.");
                        }
                }

                $this->_helper->redirector->gotoRoute(array('controller' => 'product',
                                'action' => 'list'),
                                'default',
                                true);
            }
        }

	public function editAction() {
		            
            $this->view->title = 'Add new Question';          
            $testId = $this->_request->getParam('testId');
                  
            // Creates form instance
            $form = new QuestionForm();
            $this->view->questionForm = $form; 
            
            if ($this->_request->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                 
                    $formValues = $form->getValues();
   
                    //check if at least one option is checked
                    if($formValues['checkA'] == false &&
                        $formValues['checkB'] == false &&
                        $formValues['checkC'] == false &&
                        $formValues['checkD'] == false){
                    
                        $flash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
                        $flash->clearMessages();
                        $flash->addMessage('At least one option have to be right.');
                        
                        return;
                    }
                    
                    
                    //create question
                    $question = My_Model::get('Questions')->createRow();              
                    $questionValues = array(
                        "id_test"  => $testId,
                        "obsah" =>  $formValues['otazka']
                    );   
                    $question->updateFromArray($questionValues); 
                   
                    $questionId = $question->id_otazka;
                    
                    
                    //create answers                    
                    //A
                    $optionA = My_Model::get('Options')->createRow();
                    $optionAValues = array(
                        "id_otazka" => $questionId,
                        "obsah" =>  $formValues['odpovedA'],
                        "spravnost" => $formValues['checkA']
                    );
                    $optionA->updateFromArray($optionAValues); 
                    
                    //B
                    $optionB = My_Model::get('Options')->createRow();
                    $optionBValues = array(
                        "id_otazka" => $questionId,
                        "obsah" =>  $formValues['odpovedB'],
                        "spravnost" => $formValues['checkB']
                    );
                    $optionB->updateFromArray($optionBValues); 
                    
                    //C
                    $optionC = My_Model::get('Options')->createRow();
                    $optionCValues = array(
                        "id_otazka" => $questionId,
                        "obsah" =>  $formValues['odpovedC'],
                        "spravnost" => $formValues['checkC']
                    );
                    $optionC->updateFromArray($optionCValues); 
                    
                    //D
                    $optionD = My_Model::get('Options')->createRow();
                    $optionDValues = array(
                        "id_otazka" => $questionId,
                        "obsah" =>  $formValues['odpovedD'],
                        "spravnost" => $formValues['checkD']
                    );
                    $optionD->updateFromArray($optionDValues); 
                    
                    $this->_helper->redirector->gotoRoute(array('controller' => 'question', 'action' => 'edit', 'testId' => $testId ), 'default', true);           
               }
            }
	}
}

?>