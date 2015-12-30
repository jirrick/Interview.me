<?php

/**
 * Trida reprezentujici seznam měn
 *
 */
class Currencies extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'mena';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Currency';	
    


}
	