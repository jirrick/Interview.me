<?php

/**
 * Formular pro editaci uživate
 *
 */
class UserForm extends Zend_Form
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

    	// E-mail (email)
        $lastName = $this->createElement('text', 'email');
        $lastName->addFilter('StringTrim');
        $lastName->setRequired(true);
        $lastName->setAttrib('class', 'form-control'); 
        $lastName->setAttrib('placeholder', 'E-mail');
        $lastName->removeDecorator('Label');
        $this->addElement($lastName);

        //heslo
        $pass = $this->createElement('password', 'heslo');
        $pass->setAttrib('class', 'form-control');
        $pass->setAttrib('placeholder', 'Password');
        $pass->removeDecorator('Label');

        $login = Zend_Auth::getInstance()->getIdentity();
        $user = My_Model::get('Users')->getUserByEmail($login);
        if ($user !== NULL) {
            $this->addElement($pass);
        }

		// ###################### BUTTON ######################

        $close = new Zend_Form_Element_Submit('closeButton');
        $close->setLabel('Back');
        $close->setAttrib('class', 'btn btn-default');
        $close->setDecorators(array('ViewHelper'));
        $this->addElement($close, 'closeButton');

        $save = new Zend_Form_Element_Submit('saveButton');
        $save->setLabel('Save');
        $save->setAttrib('class', 'btn btn-success');
        $save->setDecorators(array('ViewHelper'));
        $this->addElement($save, 'saveButton');

        $delete = new Zend_Form_Element_Submit('deleteButton');
        $delete->setLabel('Delete');
        $delete->setAttrib('class', 'btn btn-danger');
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
    				array('viewScript' => 'user/userFormLayout.phtml',
    					)
    				)
    			)
    		);
    }

}