<?php

/**
 * Trida reprezentujici sablonu testu
 *
 */
class Test extends My_Db_Table_Row {
	
	/**
     * Vrati instanci technologie
     *
     */
    public function getTechnology() {
        return $this->findParentRow('Technologies');
    }
    
    /**
     * Vrati instanci uzivatele-tvurce
     *
     */
    public function getCreator() {
        return $this->findParentRow('Users');
    }
	
}
	