<?php

class IndexController extends My_Controller_Action {
	
	public function init() {
            
	}

	public function indexAction() {
		
		$this->_helper->redirector->gotoRoute(array('controller' => 'candidate',
					'action' => 'index'),
					'default',
					true);

	}

}

?>