<?php

/**
 * Formular vytvareni/editace testu
 *
 */
class QuestionForm extends Zend_Form
{
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
    	$name->setAttrib('class', 'form-control'); 
    	$name->setAttrib('placeholder', 'Question');
    	$name->removeDecorator('Label');
    	$this->addElement($name);
        
           
        //A
        $this->addElement('text', 'odpovedA', array(
            'placeholder' => 'A',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkA', array(
            'name' => 'checkA',
            'disableHidden' => true
        ));
        
        //B        
        $this->addElement('text', 'odpovedB', array(
            'placeholder' => 'B',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkB', array(
            'name' => 'checkB',
            'disableHidden' => true
        ));
        
        //C        
        $this->addElement('text', 'odpovedC', array(
            'placeholder' => 'C',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkC', array(
            'name' => 'checkC',
            'disableHidden' => true
        ));
        
        //D
        $this->addElement('text', 'odpovedD', array(
            'placeholder' => 'D',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim')
        ));
                         
        $this->addElement('checkbox', 'checkD', array(
            'name' => 'checkD',
            'disableHidden' => true
        ));
        
        //submit button
        $button = $this->createElement('submit', 'Add');
    	$button->setAttrib('class', 'btn btn-success btn-md');
    	$this->addElement($button);
    }

}