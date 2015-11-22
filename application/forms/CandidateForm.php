<?php

/**
 * Formular pro vytvoření a editaci kandidáta
 *
 */
class CandidateForm extends ZendX_JQuery_Form
{
    /**
     * Inicializace formulare
     *
     */
    public function init()
    {
    	$this->setMethod(self::METHOD_POST);

    	// ################## FIRST COLUMN ###################

		// Photo (id_foto)
		// File picker
    	//$photo = new Zend_Form_Element_File('profilePhoto');
    	//$photo->setLabel('Insert profile picture:')
    	//->setDestination('/www/temp/');
    	//$photo->addValidator('Count', false, 1);
    	//$photo->addValidator('Extension', false, 'jpg,png,gif');
    	//$this->addElement($photo);

		// First name (jmeno)
    	$firstName = $this->createElement('text', 'jmeno');
    	$firstName->addFilter('StringTrim');
    	$firstName->setRequired(true);
    	$firstName->setAttrib('class', 'form-control'); 
    	$firstName->setAttrib('placeholder', 'First name');
    	$firstName->removeDecorator('Label');
    	$this->addElement($firstName);

    	// Last name (prijmeni)
    	$lastName = $this->createElement('text', 'prijmeni');
    	$lastName->addFilter('StringTrim');
    	$lastName->setRequired(true);
    	$lastName->setAttrib('class', 'form-control'); 
    	$lastName->setAttrib('placeholder', 'Surname');
    	$lastName->removeDecorator('Label');
    	$this->addElement($lastName);

    	// Date of birth (datum_narozeni)
    	// Date picker
    	$birthdate = $this->createElement('text', 'datum_narozeni');
    	$birthdate->setRequired(true);
    	$birthdate->setAttrib('class', array('form-control','date-picker')); 
    	$birthdate->removeDecorator('Label');
    	$birthdate->setAttrib('placeholder', 'Birthdate');
    	$this->addElement($birthdate);

    	// ################## SECOND COLUMN ##################

    	// Date of interview (datum_pohovoru)
    	// Date picker
    	$interviewDate = $this->createElement('text', 'datum_pohovoru');
    	$interviewDate->addFilter('StringTrim');
    	$interviewDate->setRequired(true);
    	$interviewDate->setAttrib('class', array('form-control','date-picker'));  
    	$interviewDate->removeDecorator('Label');
    	$interviewDate->setAttrib('placeholder', 'Date of interview');
    	$this->addElement($interviewDate);

    	// Status (id_status)
    	// Select box
    	$status = new Zend_Form_Element_Select('id_status');
    	$status->removeDecorator('Label');
    	$status->setAttrib('class', 'form-control'); 
    	$this->addElement($status);

    	// Position (id_pozice)
    	// Select box
    	$position = new Zend_Form_Element_Select('id_pozice');
    	$position->removeDecorator('Label');
    	$position->setAttrib('class', 'form-control'); 
    	$this->addElement($position);

    	// Technology (kandidat_technologie)
    	// Multi select
    	$technology = new Zend_Form_Element_Multiselect('kandidat_technologie');
    	$technology->removeDecorator('Label');
    	$technology->setAttrib('class', 'form-control'); 
    	$this->addElement($technology);

    	// Seniority (id_seniorita)
    	// Select box
    	$seniority = new Zend_Form_Element_Select('id_seniorita');
    	$seniority->removeDecorator('Label');
    	$seniority->setAttrib('class', 'form-control'); 
    	$this->addElement($seniority);

    	// Comment (komentar)
    	$comment = $this->createElement('textarea', 'komentar');
    	$comment->addFilter('StringTrim');
    	$comment->setAttrib('class', 'form-control'); 
    	$comment->setAttrib('rows', '3'); 
    	$comment->setAttrib('placeholder', 'Enter your comment…');
    	$comment->removeDecorator('Label');
    	$this->addElement($comment);

		// ################### ATTACHEMENTS ###################

		// Attchements (kandidat_priloha)
		// File picker

		// ###################### BUTTON ######################
    	
    	$button = $this->createElement('submit', 'Save');
    	$button->setAttrib('class', 'btn btn-success btn-md');
    	$this->addElement($button);
    }

    public function loadDefaultDecorators()
    {
    	$this->setDecorators(
    		array(
    			array(
    				'ViewScript',
    				array('viewScript' => 'candidate/formLayout.phtml',
    					)
    				)
    			)
    		);
    }

}