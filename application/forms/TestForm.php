<?php

/**
 * Formular vytvareni/editace testu
 *
 */
class TestForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
        
        //nazev testu
        $name = $this->createElement('text', 'nazev');
    	$name->addFilter('StringTrim');
    	$name->setRequired(true);
    	$name->setAttrib('class', 'form-control'); 
    	$name->setAttrib('placeholder', 'Name');
    	$name->removeDecorator('Label');
    	$this->addElement($name);

        //kategorie testu
        $technology = new Zend_Form_Element_Select('id_technologie');
    	$technology->removeDecorator('Label');
    	$technology->setAttrib('class', 'form-control'); 
    	$this->addElement($technology);
        
        //obtiznost
        $seniority = new Zend_Form_Element_Select('id_seniorita');
    	$seniority->removeDecorator('Label');
    	$seniority->setAttrib('class', 'form-control'); 
    	$this->addElement($seniority);
        
        //prideleny cas
        $time = $this->createElement('text', 'pocet_minut');
    	$time->setRequired(true);        
        $validator = new Zend_Validate_Digits();
        $validator->isValid("1234567890");
        $time->addValidator($validator);
    	$time->setAttrib('class', 'form-control'); 
    	$time->setAttrib('placeholder', 'Alloted time');
    	$time->removeDecorator('Label');
    	$this->addElement($time);
        
        //popis
        $name = $this->createElement('textarea', 'popis');
    	$name->addFilter('StringTrim');
    	$name->setRequired(true);
    	$name->setAttrib('class', 'form-control'); 
    	$name->setAttrib('placeholder', 'Description');
    	$name->removeDecorator('Label');
    	$this->addElement($name);
        
        //submit button
        $button = $this->createElement('submit', 'Save');
    	$button->setAttrib('class', 'btn btn-success btn-md');
    	$this->addElement($button);
    }

}