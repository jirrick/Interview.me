<?php

/**
 * Trida reprezentujici moznost odpovedi na otazku
 *
 */
class Option extends My_Db_Table_Row {
	
    public function updateFromArray(array $values) {
        $this->setFromArray($values);
        $this->save();
        
        return $this;
    }
}
	