<?php

/**
 * Trida reprezentujici moznost odpovedi na otazku
 *
 */
class Option extends My_Db_Table_Row {
	
	/**
     * Vrací true pokud existují odpovědi s touto možností.
     */
    public function isAnswered()
    {
        $r = My_Model::get('Responses');
        $responses = $r->fetchAll($r->select()->where('id_moznost = ?', $this->getid_moznost()));
        return $responses->count() > 0;
    }

}
	