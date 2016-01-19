<?php

/**
 * Trida reprezentujici seznam pokročilých informací
 *
 */
class AdvancedInformations extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'pokrocile_informace';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'AdvancedInformation';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Contract' => array(
           'columns' => array ('id_uvazek'), 
           'refTableClass' => 'Contracts', 
           'refColumns' => array ('id_uvazek')
        ), 
        'Currency' => array(
           'columns' => array ('id_mena'), 
           'refTableClass' => 'Currencies', 
           'refColumns' => array ('id_mena')
        ), 
    );


}
	