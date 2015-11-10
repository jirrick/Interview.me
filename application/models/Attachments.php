<?php

/**
 * Trida reprezentujici seznam priloh
 *
 */
class Attachments extends My_Db_Table  {

	/**
     * Nazev databazove tabulky
     *
     * @var string
     */
    protected $_name = 'priloha';
    
    /**
     * Nazev tridy predstavujici jeden zaznam
     *
     * @var string
     */
    protected $_rowClass = 'Attachment';	
    


}
	