<?php

/**
 * Trida reprezentujici uzivatele
 *
 */
class User extends My_Db_Table_Row {
	
	
    public function updateFromArray(array $values) {
        $this->setFromArray($values);
        $this->save();
        
        return $this;
    }
    
    
    /**
     * Vrati cele jmeno uzivatele
     * 
     */
    public function getFullName() {      
        return $this->getjmeno()." ".$this->getprijmeni();
    }
    
     /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Photo' => array(
           'columns' => array ('id_fotografie'), 
           'refTableClass' => 'Photos', 
           'refColumns' => array ('id_foto')
        ), 
    );
}
	