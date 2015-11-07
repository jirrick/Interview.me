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

        $user = $this->createElement('text', 'username');
        $user->setLabel('uživatelské jméno');
        $user->addFilter('StringTrim');
        $user->setRequired(true);
        $this->addElement($user);

        $pass = $this->createElement('password', 'password');
        $pass->setLabel('heslo');
        $pass->setRequired(true);
        $this->addElement($pass);

        $this->addElement('hidden', 'login', array('value' => 1));

        $this->addElement('submit', 'Přihlásit');
    }

}