<?php

/**
 * Trida reprezentujici otazku testu
 *
 */
class Question extends My_Db_Table_Row {
	
	/**
     * Vrati rowset s moznostmi k otazce
     *
     */
     public function getOptions() {
        return $this->findDependentRowset('Options');
    }
	    
   
}
	