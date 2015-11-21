<?php

/**
 * Trida reprezentujici seznam sablon testu
 *
 */
class Tests extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'test';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Test';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Technology' => array(
           'columns' => array ('id_technologie'), 
           'refTableClass' => 'Technologies', 
           'refColumns' => array ('id_technologie')
        ), 
        'User' => array(
           'columns' => array ('id_kdo_vytvoril'), 
           'refTableClass' => 'Users', 
           'refColumns' => array ('id_uzivatel')
        ),
    );


}
	