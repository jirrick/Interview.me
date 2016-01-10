<?php

/**
 * Formular pro vytvoření a editaci pokročilých informací ke kandidátovi
 *
 */
class AdvancedInformationsForm extends Zend_Form
{
    /**
     * Inicializace formulare
     *
     */
    public function init()
    {
    	$this->setMethod(self::METHOD_POST);
    	
    	// ################## FIRST COLUMN ###################

		// datum pohovoru
        $elem = new Zend_Form_Element_Text('datum_pohovoru');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', array('form-control','date-picker')); 
        $elem->setAttrib('placeholder', 'Pick a date…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'datum_pohovoru');

        // Multi select (perzonalista – pokročilé informace)
        $elem = new Zend_Form_Element_Multiselect('perzonalista_informace');
        $elem->removeDecorator('Label');
        $elem->setAttrib('placeholder', 'Pick an interviewers…');
        $elem->setAttrib('class', 'form-control'); 
        $this->addElement($elem, 'perzonalista_informace');

        // vzdělání
        $elem = new Zend_Form_Element_Text('vzdelani');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Education…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'vzdelani');

        // motivace
        $elem = new Zend_Form_Element_Textarea('motivace');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Motivation…');
        $elem->setAttrib('rows', '1'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'motivace');

        // preferovaná práce
        $elem = new Zend_Form_Element_Textarea('preferovana_prace');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Kind of work preferred…');
        $elem->setAttrib('rows', '1'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'preferovana_prace');

        // ambice
        $elem = new Zend_Form_Element_Textarea('ambice');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Ambitions…');
        $elem->setAttrib('rows', '1'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'ambice');

        // jazyky
        $elem = new Zend_Form_Element_Text('jazyky');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Languages…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'jazyky');

        // cestování
        $elem = new Zend_Form_Element_Text('cestovani');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Travelling…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'cestovani');

        // ################## SECOND COLUMN ###################

        // plusy_minusy
        $elem = new Zend_Form_Element_Textarea('plusy_minusy');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Pluses/Minuses…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'plusy_minusy');

        // zkusenosti_v_tymu
        $elem = new Zend_Form_Element_Textarea('zkusenosti_v_tymu');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Team work experience…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'zkusenosti_v_tymu');

        // pracovni_misto
        $elem = new Zend_Form_Element_Textarea('pracovni_misto');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Domain/Areas of work…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'pracovni_misto');

        // knowhow
        $elem = new Zend_Form_Element_Textarea('knowhow');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Skills and technologies…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'knowhow');

        // dalsi_informace
        $elem = new Zend_Form_Element_Textarea('dalsi_informace');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Other informations…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'dalsi_informace');

        // ################## THIRD COLUMN ###################

        // shrnuti_pohovoru
        $elem = new Zend_Form_Element_Textarea('shrnuti_pohovoru');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Summary of the interview…');
        $elem->setAttrib('rows', '3'); 
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'shrnuti_pohovoru');

        // idealni_pozice
        $elem = new Zend_Form_Element_Text('idealni_pozice');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Ideal for position/project…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'idealni_pozice');

        // datum_zahajeni
        $elem = new Zend_Form_Element_Text('datum_zahajeni');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', array('form-control','date-picker')); 
        $elem->setAttrib('placeholder', 'Posible starting date…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'datum_zahajeni');

        // uvazek
        $elem = new Zend_Form_Element_Select('id_uvazek');
        $elem->removeDecorator('Label');
        $elem->setAttrib('class', 'form-control'); 
        $this->addElement($elem, 'id_uvazek');

        // mzda
        $elem = new Zend_Form_Element_Text('mzda');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', 'form-control'); 
        $elem->setAttrib('placeholder', 'Salary…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'mzda');

        $elem = new Zend_Form_Element_Select('id_mena');
        $elem->removeDecorator('Label');
        $elem->setAttrib('class', 'form-control'); 
        $this->addElement($elem, 'id_mena');

        // datum_pristiho_kontaktu
        $elem = new Zend_Form_Element_Text('datum_pristiho_kontaktu');
        $elem->addFilter('StringTrim');
        $elem->setAttrib('class', array('form-control','date-picker')); 
        $elem->setAttrib('placeholder', 'Pick a date…');
        $elem->removeDecorator('Label');
        $this->addElement($elem, 'datum_pristiho_kontaktu');


		// ###################### BUTTON ######################

        $save = new Zend_Form_Element_Submit('saveButton');
        $save->setLabel('Save');
        $save->setAttrib('class', 'btn btn-success');
        $save->setDecorators(array('ViewHelper'));
        $this->addElement($save, 'saveButton');
    }

    public function loadDefaultDecorators()
    {
    	$this->setDecorators(
    		array(
    			array(
    				'ViewScript',
    				array('viewScript' => 'candidate/advancedInformationsFormLayout.phtml',
    					)
    				)
    			)
    		);
    }

}