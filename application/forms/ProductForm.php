<?php

/**
 * Formular pridani produktu
 *
 */
class ProductForm extends Zend_Form {
	
	/**
	 * Inicializace formulare
	 *
	 */
	public function init() {
		
		// nastaveni metody odeslani
		$this->setMethod('post');
		
		// nastaveni polozek formulare
		
		$manufacturers = array('' => '...');
		foreach (My_Model::get('Manufacturers')->fetchAll() as $manufacturer) {
		    $manufacturers[$manufacturer->getId()] = $manufacturer->getName();
		}	
		$this->addElement('select', 'manufacturer_id', array(
            'label' => 'Výrobce:',
            'class' => 'input',
            'required' => true,
            'filters' => array('StringTrim'),
		    'multiOptions' => $manufacturers,
        ));
        
		
		
		$this->addElement('text', 'title', array(
			'label' => 'Název:',
			'class' => 'input',
			'required' => true,
			'filters' => array('StringTrim')
		));
		
		$this->addElement('text', 'price', array(
			'label' => 'Cena:',
			'class' => 'input',
			'required' => true,
			'filters' => array('Digits'),
			'validators' => array(
				'Digits',
				array('validator' => 'GreaterThan', 'options' => array(0))
			)
		));
		
					
		$this->addElement('select', 'currency', array(
			'label' => 'Měna:',
			'class' => 'input',
			'required' => true,
			'filters' => array('StringTrim'),
			'multiOptions' => array('czk' => 'CZK', 'eur' => 'EUR'),
		));
		
		$this->addElement('textarea', 'description', array(
			'label' => 'Popis:',
			'class' => 'textarea',
			'required' => true,
			'filters' => array('StringTrim')
		));
		

		$folders = array();
        foreach (My_Model::get('Folders')->fetchAll() as $folder) {
            $folders[$folder->getId()] = $folder->getName();
        }   
        $this->addElement('multiCheckbox', 'folders', array(
            'label' => 'Rubrika:',
            'required' => true,
            'filters' => array('StringTrim'),
            'multiOptions' => $folders,
        ));        
		
		
		$this->addElement('submit', 'submit', array(
			'ignore' => true,
			'class' => 'submit',
			'label' => 'Přidat'
		));
		
	}
	
	/**
	 * Upravi formular do podoby editacniho formulare
	 */
	public function setModifyMode() {
		$this->getElement('submit')->setLabel('Upravit');
	}

	
}

?>