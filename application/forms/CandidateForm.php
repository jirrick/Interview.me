<?php

/**
 * Formular pro vytvoření a editaci kandidáta
 *
 */
class CandidateForm extends Zend_Form
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
    	$profilePhotoPicker = new Zend_Form_Element_File('profilePhoto');
    	$profilePhotoPicker->setDestination(PUBLIC_PATH . '/temp');
    	$profilePhotoPicker->addValidator('Count', false, 1);
    	$profilePhotoPicker->addValidator('Size', false, 2097152);
    	$profilePhotoPicker->removeDecorator('Label');
    	$this->addElement($profilePhotoPicker, 'profilePhoto');

    	$avatar = new Zend_Form_Element_Image('avatar');
    	$avatar->setAttrib('class', array("profile-user-img", "img-responsive", "img-circle"));
    	$this->addElement($avatar, 'avatar');


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
    	$this->addElement($status, 'id_status');

    	// Position (id_pozice)
    	// Select box
    	$position = new Zend_Form_Element_Select('id_pozice');
    	$position->removeDecorator('Label');
    	$position->setAttrib('class', 'form-control'); 
    	$this->addElement($position, 'id_pozice');

    	// Technology (kandidat_technologie)
    	// Multi select
    	$technology = new Zend_Form_Element_Multiselect('kandidat_technologie');
    	$technology->removeDecorator('Label');
    	$technology->setAttrib('class', 'form-control'); 
    	$this->addElement($technology, 'kandidat_technologie');

    	// Seniority (id_seniorita)
    	// Select box
    	$seniority = new Zend_Form_Element_Select('id_seniorita');
    	$seniority->removeDecorator('Label');
    	$seniority->setAttrib('class', 'form-control'); 
    	$this->addElement($seniority, 'id_seniorita');

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
        $attachments = new Zend_Form_Element_File('attachments');
        $attachments->setDestination(PUBLIC_PATH . '/temp');
        $attachments->addValidator('Count', false, array('min' => 0, 'max' => 4));
        $attachments->addValidator('Size', false, 2097152);
        $attachments->setMultiFile(4);
        $attachments->removeDecorator('Label');
        $this->addElement($attachments, 'attachments');

        $attachmentsCheckGroup = new Zend_Form_Element_MultiCheckbox('attachmentsCheckGroup');
        $comment->removeDecorator('Label');
        $this->addElement($attachmentsCheckGroup, 'attachmentsCheckGroup');

		// ###################### BUTTON ######################

        $close = new Zend_Form_Element_Submit('closeButton');
        $close->setLabel('Back');
        $close->setAttrib('class', 'btn btn-default btn-lg');
        $close->setDecorators(array('ViewHelper'));
        $this->addElement($close, 'closeButton');

        $save = new Zend_Form_Element_Submit('saveButton');
        $save->setLabel('Save');
        $save->setAttrib('class', 'btn btn-success btn-lg');
        $save->setDecorators(array('ViewHelper'));
        $this->addElement($save, 'saveButton');

        $delete = new Zend_Form_Element_Submit('deleteButton');
        $delete->setLabel('Delete');
        $delete->setAttrib('class', 'btn btn-danger btn-lg');
        $delete->setAttrib(
            'onclick', 
            'if (confirm("Are you sure?")) { document.form.submit(); } return false;'
            );
        $delete->setDecorators(array('ViewHelper'));
        $this->addElement($delete, 'deleteButton');
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