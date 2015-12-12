<?php

/**
 * Trida reprezentujici seznam kandidatu
 *
 */
class Messages extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'zprava';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Message';	
    
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
    );


}
	