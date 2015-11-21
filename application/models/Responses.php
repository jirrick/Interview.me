<?php

/**
 * Trida reprezentujici seznam odpovedi kandidatu na otazky v testu
 *
 */
class Responses extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'odpoved';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Response';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Assignment' => array(
           'columns' => array ('id_prirazeny_test'), 
           'refTableClass' => 'Assignments', 
           'refColumns' => array ('id_prirazeny_test')
        ), 
        'Question' => array(
           'columns' => array ('id_otazka'), 
           'refTableClass' => 'Questions', 
           'refColumns' => array ('id_otazka')
        ), 
        'Option' => array(
           'columns' => array ('id_moznost'), 
           'refTableClass' => 'Options', 
           'refColumns' => array ('id_moznost')
        ), 
    );


}
	