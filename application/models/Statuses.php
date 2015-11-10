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


}
	