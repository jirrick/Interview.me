<?php

/**
 * Trida reprezentujici seznam vyrobcu produktu
 *
 */
class Names extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'names';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Name';	


}
	