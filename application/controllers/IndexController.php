<?php

/**
 * Controller pro uvodni a informaci stranky
 *
 */
class IndexController extends Zend_Controller_Action
{

    /**
     * Uvodni stranka
     *
     */
    public function indexAction()
    {
    	$names = My_Model::get('Names')->fetchAll();

        $this->view->title = 'Zdar';
    	$this->view->names = $names;
    }
}
