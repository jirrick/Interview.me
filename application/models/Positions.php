<?php

/**
 * Trida reprezentujici seznam pozic
 *
 */
class Positions extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'pozice';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Position';	


}
	