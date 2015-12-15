<?php

/**
 * Trida reprezentujici uzivatele
 *
 */
class User extends My_Db_Table_Row {
	
    /**
     * Vrati cele jmeno uzivatele
     * 
     */
    public function getFullName() {      
        return $this->getjmeno()." ".$this->getprijmeni();
    }
    
     /**
     * Vrati instanci Photo
     *
     */
    public function getFoto() {
        return $this->findParentRow('Photos');
    }
}
	