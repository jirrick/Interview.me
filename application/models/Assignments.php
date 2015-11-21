<?php

/**
 * Trida reprezentujici seznam prirazenych testu
 *
 */
class Assignments extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'prirazeny_test';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Assignment';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Test' => array(
           'columns' => array ('id_test'), 
           'refTableClass' => 'Testss', 
           'refColumns' => array ('id_test')
        ), 
        'User' => array(
           'columns' => array ('id_kdo_priradil'), 
           'refTableClass' => 'Users', 
           'refColumns' => array ('id_uzivatel')
        ), 
        'Status' => array(
           'columns' => array ('id_status'), 
           'refTableClass' => 'Statuses', 
           'refColumns' => array ('id_status')
        ), 
        'Candidate' => array(
           'columns' => array ('id_kandidat'), 
           'refTableClass' => 'Candidatess', 
           'refColumns' => array ('id_kandidat')
        ), 
    );


}
	