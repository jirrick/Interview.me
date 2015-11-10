<?php

/**
 * Trida reprezentujici seznam technologii
 *
 */
class Technologies extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'technologie';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Technology';	


}
	