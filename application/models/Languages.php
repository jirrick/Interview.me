<?php

/**
 * Trida reprezentujici seznam prirazenych testu
 *
 */
class Languages extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'jazyk';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Language';	

}
	