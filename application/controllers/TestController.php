<?php

class TestController extends Zend_Controller_Action {
	
	public function init() {	
		
	}

	public function editAction() {
		
		$this->view->title = 'Editace testu';
		// rovnaka akcia pre new aj pre edit, title sa zmeni ak pojde o new, tak ako na cviku

	}

	public function resultAction() {
		
		$this->view->title = 'Výsledky testů';

	}
	
}

?>