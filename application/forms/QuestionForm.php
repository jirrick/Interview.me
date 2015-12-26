<?php

/**
 * Formular vytvareni/editace testu
 *
 */
class QuestionForm extends Zend_Form
{
    private $_question = null;
    private $_count = -1;
    private $_languages = array();
    
    public function __construct(array $params = array())
    {
        $this->_question = My_Model::get('Questions')->getById($params['questionId']);
        if (intval($params['count']) > -1 ) $this->_count = intval($params['count']);
        
        $languages = My_Model::get('Languages')->fetchAll();
        $this->_languages[0] = "None";
        foreach ($languages as $l){
            $this->_languages[$l->getid_jazyk()] = $l->getnazev();
        }
               
        parent::__construct();
    }
   
    /**
     * Inicializace formulare
     *
     */
    
    public function init()
    {
        $this->setMethod(self::METHOD_POST);
        
        $name = $this->createElement('text', 'otazka');
    	$name->addFilter('StringTrim');
    	$name->setRequired(true);
    	$name->setAttrib('class', 'form-control dd-test'); 
    	$name->removeDecorator('Label');
        if($this->_question === null){
            $name->setAttrib('placeholder', 'Question');
        } else {
            $name->setValue($this->_question->getobsah());
        }
        $this->addElement($name);
        
        $language = $this->createElement('select', 'language', array(
                    'name' => 'language',
                    'class' => '',
                    'value' => ($this->_question === null ? 0 : $this->_question->getid_jazyk()),
                    'multiOptions' => $this->_languages
                ));
        
        $this->addElement($language);
        
        if($this->_question === null){ // tvorba prazdnych poli
            $optionsNames = array('', 'A', 'B', 'C', 'D', 'E');
            $count = (($this->_count > -1 && $this->_count <=5) ? $this->_count : 3);
            
            for ($i = 1; $i <= $count; $i++) {
                $this->addElement('text', 'odpoved' . strval($i), array(
                    'placeholder' => $optionsNames[$i],
                    'class' => 'input dd-test',
                    'required' => true,
                    'label' => false,
                    'filters' => array('StringTrim')
                ));
                
                $this->addElement('checkbox', 'check' . strval($i), array(
                    'class' => 'dd-chc',
                    'disableHidden' => true
                ));
            }
            // hidden element pocet otazek
            $this->addElement('hidden', 'count', array(
            'value' => $count
            ));      
            
        } else { // naplneni poli podle existujicich dat
            $i = 1;
            $options = $this->_question->getOptions();
            foreach ($options as $o) {
                $this->addElement('text', 'odpoved' . strval($i), array(
                    'value' => $o->getobsah(),
                    'class' => 'input dd-test',
                    'required' => true,
                    'label' => false,
                    'filters' => array('StringTrim')
                ));
                
                $this->addElement('checkbox', 'check' . strval($i), array(
                    'value' => $o->getspravnost(),
                    'class' => 'dd-chc',
                    'disableHidden' => true
                ));                
                $i++;
            }
            // hidden element pocet otazek
            $this->addElement('hidden', 'count', array(
            'value' => ($i - 1)
            ));  
        }
        
        //submit button
        $button = $this->createElement('submit', 'Add');
    	$button->setAttrib('class', 'btn btn-success btn-md dd-test');
    	$this->addElement($button);
    }

}