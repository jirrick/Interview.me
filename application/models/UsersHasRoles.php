<?php

/**
 * Trida reprezentujici vazbu mezi uzivatem a roli
 *
 */
class UsersHasRoles extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'uzivatel_role';
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'User' => array(
           'columns' => array ('id_uzivatel'), 
           'refTableClass' => 'Users', 
           'refColumns' => array ('id_uzivatel')
        ), 
        'Role' => array(
           'columns' => array ('id_role'), 
           'refTableClass' => 'Roles', 
           'refColumns' => array ('id_role')
        ), 
    );	


}
	