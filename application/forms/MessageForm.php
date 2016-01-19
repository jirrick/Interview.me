<?php

/**
 * Formular pro odeslání direct chat zprávy
 *
 */
class MessageForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    public function init()
    {
    	$this->setMethod(self::METHOD_POST);

		// Message
    	$message = new Zend_Form_Element_Text('message');
    	$message->addFilter('StringTrim');
    	$message->setRequired(true);
    	$message->setAttrib('class', 'form-control'); 
    	$message->setAttrib('placeholder', 'Type Message…');
    	$message->removeDecorator('Label');
    	$this->addElement($message, 'message');

    	// Send button
        $send = new Zend_Form_Element_Submit('sendButton');
        $send->setLabel('Send');
        $send->setAttrib('class', 'btn btn-success btn-flat');
        $send->setDecorators(array('ViewHelper'));
        $this->addElement($send, 'sendButton');
    }

    public function loadDefaultDecorators()
    {
    	$this->setDecorators(
    		array(
    			array(
    				'ViewScript',
    				array('viewScript' => 'candidate/sendMessageFormLayout.phtml',
    					)
    				)
    			)
    		);
    }

}