<?php

/**
 * Trida reprezentujici seznam stupnu seniority
 *
 */
class Seniorities extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'seniorita';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Seniority';	


}
	