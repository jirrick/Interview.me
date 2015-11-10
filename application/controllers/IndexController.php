<?php

class IndexController extends Zend_Controller_Action {
	
	public function init() {

	}

	public function indexAction() {
		
		$this->view->title = 'Dashboard';

	}

	public function aboutAction() {
		
		$this->view->title = 'About';

	}
	
}

?>