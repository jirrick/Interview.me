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
        $firstName->setLabel('Jméno');
        $firstName->addFilter('StringTrim');
        $firstName->setRequired(true);
        $this->addElement($firstName);
        
        //příjmení$firstName
        $surName = $this->createElement('text', 'prijmeni');
        $surName->setLabel('Příjmení');
        $surName->addFilter('StringTrim');
        $surName->setRequired(true);
        $this->addElement($surName);

        //email (login)
        $email = $this->createElement('text', 'email');
        $email->setLabel('Email');
        $email->addFilter('StringTrim');
        $email->setRequired(true);
        $this->addElement($email);
        
        //heslo
        $pass = $this->createElement('password', 'heslo');
        $pass->setLabel('Heslo');
        $pass->setRequired(true);
        $this->addElement($pass);
        
        //opakování hesla
        $passAgain = $this->createElement('password', 'heslo2');
        $passAgain->setLabel('Heslo znovu');
        $passAgain->setRequired(true);
        $this->addElement($passAgain);
        
        $this->addElement('hidden', 'registration', array('value' => 1));

        $this->addElement('submit', 'Registrovat');
    }

}