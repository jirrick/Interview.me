<?php

/**
 * Trida reprezentujici seznam moznosti odpovedi na otazky
 *
 */
class Options extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'moznost';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Option';	
    
        
     /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Question' => array(
           'columns' => array ('id_otazka'), 
           'refTableClass' => 'Questions', 
           'refColumns' => array ('id_otazka')
        ), 
        'Language' => array(
           'columns' => array ('id_jazyk'), 
           'refTableClass' => 'Languages', 
           'refColumns' => array ('id_jazyk')
        ), 
    );   
     

}
	