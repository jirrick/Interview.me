<?php

/**
 * Formular vyplneni testu
 *
 */
class TestFillForm extends Zend_Form
{
    private $_test;
    
    public function __construct(array $params = array())
    {
        $this->_test = My_Model::get('Tests')->getById($params['testId']);
        parent::__construct();
    }
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
        
        $questions = $this->_test->getQuestions();
        foreach ($questions as $q) { 
            $element = new Zend_Form_Element_MultiCheckbox($q->getid_otazka());
            $element->setLabel($q->getobsah());
            $element->addDecorator('Label',array('placement' => 'prepend'));
            $options = $q->getOptions();
            foreach ($options as $o) { 
                 $element->addMultiOption($o->getid_moznost(), $o->getobsah());
            }
            $this->addElement($element);
        }
        
        //submit button
        $button = $this->createElement('submit', 'Submit test');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}