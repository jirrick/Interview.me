<?php

/**
 * Trida reprezentujici vazbu mezi kandidatem a prilohami
 *
 */
class CandidatesHasAttachments extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'kandidat_priloha';
    
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
        'Attachment' => array(
           'columns' => array ('id_priloha'), 
           'refTableClass' => 'Attachments', 
           'refColumns' => array ('id_priloha')
        ), 
    );	


}
	