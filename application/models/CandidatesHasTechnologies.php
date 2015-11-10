<?php

/**
 * Trida reprezentujici vazbu mezi kandidatem a technologii
 *
 */
class CandidatesHasTechnologies extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'kandidat_technologie';
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (  
        'Candidate' => array(
           'columns' => array ('id_kandidat'), 
           'refTableClass' => 'Candidates', 
           'refColumns' => array ('id_kandidat')
        ),
        'Technology' => array(
           'columns' => array ('id_technologie'), 
           'refTableClass' => 'Technologies', 
           'refColumns' => array ('id_technologie')
        ), 
    );

}
	