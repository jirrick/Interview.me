<?php

/**
 * Formular pro vytvoření a editaci kandidáta
 *
 */
class TestCommentForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    public function init()
    {
    	$this->setMethod(self::METHOD_POST);
    	
    	// Comment (komentar)
    	$comment = $this->createElement('textarea', 'komentar');
    	$comment->addFilter('StringTrim');
    	$comment->setAttrib('class', 'form-control'); 
    	$comment->setAttrib('rows', '3'); 
    	$comment->setAttrib('placeholder', 'Enter your comment…');
    	$comment->removeDecorator('Label');
    	$this->addElement($comment);

        $save = new Zend_Form_Element_Submit('saveButton');
        $save->setLabel('Finish Evaluation');
        $save->setAttrib('class', 'btn btn-success');
        $this->addElement($save, 'saveButton');
    }

}