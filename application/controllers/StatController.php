<?php

class StatController extends My_Controller_Action {
	
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
    }

	public function indexAction() {
		
		$this->view->title = 'Statistics';

	}
	
}

?>