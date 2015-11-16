<?php

/**
 * Formular prihlaseni uzivatele
 *
 */
class RegistrationForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
 
        //jméno
        $firstName = $this->createElement('text', 'jmeno');
        $firstName->addFilter('StringTrim');
        $firstName->setRequired(true);
        $firstName->setAttrib('class', 'form-control'); 
        $firstName->setAttrib('placeholder', 'Firstname');
        $this->addElement($firstName);
       
        //příjmení$firstName
        $surName = $this->createElement('text', 'prijmeni');
        $surName->addFilter('StringTrim');
        $surName->setRequired(true);
        $surName->setAttrib('class', 'form-control'); 
        $surName->setAttrib('placeholder', 'Surename');
        $this->addElement($surName);

        //email (login)
        $email = $this->createElement('text', 'email');
        $email->addFilter('StringTrim');
        $email->setRequired(true);
        $email->setAttrib('class', 'form-control');
        $email->setAttrib('placeholder', 'E-mail');
        $this->addElement($email);
        
        //heslo
        $pass = $this->createElement('password', 'heslo');
        $pass->setRequired(true);
        $pass->setAttrib('class', 'form-control');
        $pass->setAttrib('placeholder', 'Password');
        $this->addElement($pass);
        
        //opakování hesla
        $passAgain = $this->createElement('password', 'heslo2');
        $passAgain->setRequired(true);
        $passAgain->setAttrib('class', 'form-control');
        $passAgain->setAttrib('placeholder', 'Password Again');
        $this->addElement($passAgain);
        
        $this->addElement('hidden', 'registration', array('value' => 1));
        
                $this->addElement('checkbox', 'admin', array(
            'label' => 'Admin account',
            'name' => 'admin',
            'disableHidden' => true
        ));

        $button = $this->createElement('submit', 'Register');
        $button->setAttrib('class', 'btn btn-primary btn-block btn-flat');
        $this->addElement($button);
    }

}