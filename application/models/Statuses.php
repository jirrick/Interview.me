<?php

/**
 * Trida reprezentujici seznam stavu zadosti
 *
 */
class Statuses extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'status';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Status';	
    
    /**
     * Ziska z DB aktualni ID odpovidaji status kodu
     *
     */
    public function getStatusID($statusCode){
        $select = $this->select()->where('kod = ?', $statusCode);
        $row = $this->fetchRow($select);
        return $row->getid_status();
    } 


}
	