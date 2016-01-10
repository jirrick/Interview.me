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
            $options = $q->getOptions();
            if (count($options) == 0) {
                // otevrena odpoved
                $text = $this->createElement('textarea', $q->getid_otazka());
                $text->addFilter('StringTrim');
                $text->setRequired(true);
                $text->setLabel($q->getobsah());
                $text->setAttrib('placeholder', 'Fill in your answer');
                $text->setDecorators(array(array('ViewScript', array('viewScript'=>'TextArea.php', 'languageId'=>$q->getid_jazyk()))));
                $this->addElement($text);  
            } else {
                // multicheckbox
                $multicheck = new Zend_Form_Element_MultiCheckbox($q->getid_otazka());
                $multicheck->setLabel($q->getobsah());
                $multicheck->setRequired(true);
                foreach ($options as $o) { 
                    $multicheck->addMultiOption($o->getid_moznost(), $o->getobsah());
                }
                $multicheck->setDecorators(array(array('ViewScript', array('viewScript'=>'MultiCheckbox.php', 'languageId'=>$q->getid_jazyk()))));
                $this->addElement($multicheck);          
            }
        }
        
        //submit button
        $button = $this->createElement('submit', 'Submit');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}