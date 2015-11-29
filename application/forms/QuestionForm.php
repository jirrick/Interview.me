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
    	$name->setAttrib('class', 'form-control dd-test'); 
    	$name->setAttrib('placeholder', 'Question');
    	$name->removeDecorator('Label');
    	$this->addElement($name);
        
           
        //A
        $this->addElement('text', 'odpovedA', array(
            'placeholder' => 'A',
            'class' => 'input dd-test',
            'required' => true,
            'label' => false,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkA', array(
            'name' => 'checkA',
            'class' => 'dd-test',
            'disableHidden' => true
        ));
        
        //B        
        $this->addElement('text', 'odpovedB', array(
            'placeholder' => 'B',
            'class' => 'input dd-test',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkB', array(
            'name' => 'checkB',
            'class' => 'dd-test',
            'disableHidden' => true
        ));
        
        //C        
        $this->addElement('text', 'odpovedC', array(
            'placeholder' => 'C',
            'class' => 'input dd-test',
            'required' => true,
            'filters' => array('StringTrim')
        ));
        
        $this->addElement('checkbox', 'checkC', array(
            'name' => 'checkC',
            'class' => 'dd-test',
            'disableHidden' => true
        ));
        
        //D
        $this->addElement('text', 'odpovedD', array(
            'placeholder' => 'D',
            'class' => 'input dd-test',
            'required' => true,
            'filters' => array('StringTrim')
        ));
                         
        $this->addElement('checkbox', 'checkD', array(
            'name' => 'checkD',
            'class' => 'dd-test',
            'disableHidden' => true
        ));
        
        //submit button
        $button = $this->createElement('submit', 'Add');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}