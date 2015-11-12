<?php

/**
 * Formular prihlaseni uzivatele
 *
 */
class LoginForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    
        public function init()
    {
        $this->setMethod(self::METHOD_POST);

        $login = $this->createElement('text', 'email');
        $login->addFilter('StringTrim');
        $login->setRequired(true);
        $login->setAttrib('class', 'form-control'); 
        $login->setAttrib('placeholder', 'E-mail');
        $this->addElement($login);

        $pass = $this->createElement('password', 'heslo');
        $pass->setRequired(true);
        $pass->setAttrib('class', 'form-control'); 
        $pass->setAttrib('placeholder', 'Password');
        $this->addElement($pass);

        $this->addElement('hidden', 'login', array('value' => 1));

        $button = $this->createElement('submit', 'Login');
        $button->setAttrib('class', 'btn btn-primary btn-block btn-flat');
        $this->addElement($button);
    }

}