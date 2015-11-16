<?php

/**
 * Trida reprezentujici seznam roli uzivatelu
 *
 */
class Roles extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'role';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Role';	


}
	