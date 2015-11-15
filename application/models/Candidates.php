<?php

/**
 * Trida reprezentujici seznam kandidatu
 *
 */
class Candidates extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'kandidat';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Candidate';	
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Avatar' => array(
           'columns' => array ('id_foto'), 
           'refTableClass' => 'Avatars', 
           'refColumns' => array ('id_foto')
        ), 
        'Position' => array(
           'columns' => array ('id_pozice'), 
           'refTableClass' => 'Positions', 
           'refColumns' => array ('id_pozice')
        ), 
        'Seniority' => array(
           'columns' => array ('id_seniorita'), 
           'refTableClass' => 'Seniorities', 
           'refColumns' => array ('id_seniorita')
        ), 
        'Status' => array(
           'columns' => array ('id_status'), 
           'refTableClass' => 'Statuses', 
           'refColumns' => array ('id_status')
        ), 
        'Photo' => array(
           'columns' => array ('id_foto'), 
           'refTableClass' => 'Photos', 
           'refColumns' => array ('id_foto')
        ), 
    );


}
	