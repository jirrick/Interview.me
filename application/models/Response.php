<?php

/**
 * Trida reprezentujici kandidatovu odpoved
 *
 */
class Response extends My_Db_Table_Row {
	

    public function updateFromArray(array $values) {
        $this->setFromArray($values);
        $this->save();
        
        return $this;
    }
	
}
	