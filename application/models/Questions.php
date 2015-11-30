<?php

/**
 * Trida reprezentujici seznam otazek testu
 *
 */
class Questions extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'otazka';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Question';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Test' => array(
           'columns' => array ('id_test'), 
           'refTableClass' => 'Tests', 
           'refColumns' => array ('id_test')
        ), 
    );


}
	