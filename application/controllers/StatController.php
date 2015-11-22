<?php

class StatController extends My_Controller_Action {
	
	public function init()
	{
		$this->view->user = $this->getUser();
    }

	public function indexAction() {
		
		$this->view->title = 'Statistics';

	}
	
}

?>