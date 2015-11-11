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
        $login->setLabel('Email');
        $login->addFilter('StringTrim');
        $login->setRequired(true);
        $this->addElement($login);

        $pass = $this->createElement('password', 'heslo');
        $pass->setLabel('Heslo');
        $pass->setRequired(true);
        $this->addElement($pass);

        $this->addElement('hidden', 'login', array('value' => 1));

        $this->addElement('submit', 'Přihlásit');
    }

}