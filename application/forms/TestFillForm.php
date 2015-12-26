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
        $this->setName('fillTest');
          
        $questions = $this->_test->getQuestions();
        foreach ($questions as $q) {
            $element = new Zend_Form_Element_MultiCheckbox($q->getid_otazka());
            $element->setLabel($q->getobsah());
            $element->class = 'question';
            $element->setRequired(true);
            $options = $q->getOptions();
            foreach ($options as $o) { 
                 $element->addMultiOption($o->getid_moznost(), $o->getobsah());
            }
            $element->setDecorators(array(array('ViewScript', array('viewScript'=>'MultiCheckbox.php', 'languageId'=>$q->getid_jazyk()))));
            $this->addElement($element);
        }
        
        //submit button
        $button = $this->createElement('submit', 'Submit');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}