<?php

/**
 * Trida reprezentujici seznam uzivatelu
 *
 */
class Users extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'uzivatel';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'User';	


}
	