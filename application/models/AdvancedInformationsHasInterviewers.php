<?php

/**
 * Trida reprezentujici vazbu mezi pokročilými informacemi a perzonalisty.
 *
 */
class AdvancedInformationsHasInterviewers extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'perzonalista_pokrocile_informace';
    
    /**
     * Reference
     * 
     * @var array
     */
    protected $_referenceMap = array (
      'AdvancedInformation' => array(
           'columns' => array ('id_pokrocile_informace'), 
           'refTableClass' => 'AdvancedInformations', 
           'refColumns' => array ('id_pokrocile_informace')
        ),
        'User' => array(
           'columns' => array ('id_uzivatel'), 
           'refTableClass' => 'Users', 
           'refColumns' => array ('id_uzivatel')
        ), 
    );
}
	