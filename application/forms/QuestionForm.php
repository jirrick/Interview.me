<?php

/**
 * Formular vytvareni/editace testu
 *
 */
class QuestionForm extends Zend_Form
{
    private $_question = null;
    private $_languages = array();
    
    public function __construct(array $params = array())
    {
        $this->_question = My_Model::get('Questions')->getById($params['questionId']);
        
        $languages = My_Model::get('Languages')->fetchAll();
        $this->_languages[0] = "None";
        foreach ($languages as $l){
            $this->_languages[$l->getid_jazyk()] = $l->getnazev();
        }
               
        parent::__construct();
    }
   
    /**
     * Inicializace formulare
     *
     */
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
        
        $name = $this->createElement('text', 'otazka');
    	$name->addFilter('StringTrim');
    	$name->setRequired(true);
    	$name->setAttrib('class', 'form-control dd-test'); 
    	$name->removeDecorator('Label');
        if($this->_question === null){
            $name->setAttrib('placeholder', 'Question');
        } else {
            $name->setValue($this->_question->getobsah());
        }
        $this->addElement($name);
        
        $language = $this->createElement('select', 'language', array(
                    'name' => 'language',
                    'class' => '',
                    'value' => ($this->_question === null ? 0 : $this->_question->getid_jazyk()),
                    'multiOptions' => $this->_languages
                ));
        
        $this->addElement($language);
        
        if($this->_question === null){
            $optionsNames = array('A', 'B', 'C', 'D');
            for ($i = 0; $i < 4; $i++) {
                $this->addElement('text', 'odpoved' . $optionsNames[$i], array(
                    'placeholder' => $optionsNames[$i],
                    'class' => 'input dd-test',
                    'required' => true,
                    'label' => false,
                    'filters' => array('StringTrim')
                ));
                
                $this->addElement('checkbox', 'check' . $optionsNames[$i], array(
                    'name' => 'check' . $optionsNames[$i],
                    'class' => 'dd-chc',
                    'disableHidden' => true
                ));
            }      
            
        } else {
            $optionsNames = array('A', 'B', 'C', 'D');
            $i = 0;
            $options = $this->_question->getOptions();
            foreach ($options as $o) {
                $this->addElement('text', 'odpoved' . $optionsNames[$i], array(
                    'value' => $o->getobsah(),
                    'class' => 'input dd-test',
                    'required' => true,
                    'label' => false,
                    'filters' => array('StringTrim')
                ));
                
                $this->addElement('checkbox', 'check' . $optionsNames[$i], array(
                    'value' => $o->getspravnost(),
                    'name' => 'check' . $optionsNames[$i],
                    'class' => 'dd-chc',
                    'disableHidden' => true
                ));                
                $i++;
            } 
        }
        
        
        
        //submit button
        $button = $this->createElement('submit', 'Add');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}