<?php

/**
 * Trida reprezentujici seznam úvazků
 *
 */
class Contracts extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'uvazek';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Contract';	
    


}
	