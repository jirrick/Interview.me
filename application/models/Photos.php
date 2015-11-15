<?php

/**
 * Trida reprezentujici seznam fotografií
 *
 */
class Photos extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'fotografie';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Photo';	


}
	