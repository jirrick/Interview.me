<?php
/**
 * Controller pro uvodni a informaci stranky
 *
 */
class IndexController extends Zend_Controller_Action {
	
	/**
	 * Uvodni konfigurace controlleru
	 *
	 */
	public function init() {
		
		
		
	}
	
	/**
	 * Uvodni stranka
	 *
	 */
	public function indexAction() {
		
		$this->view->title = 'VITAJTE';

	}
	
}

?>