<?php
/**
 * Trida reprezentujici obecny tabulku v databazi
 *
 */
class My_Db_Table extends Zend_Db_Table_Abstract {
    
    /**
     * Navrati zaznam dle zadaneho primarniho klice
     *
     * @param string $id
     * @return Product
     */
    public function getById($id) {
        return $this->find($id)->current(); 
    }
    
}