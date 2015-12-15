<?php

/**
 * Trida reprezentujici otazku testu
 *
 */
class Question extends My_Db_Table_Row {
	
	/**
     * Vrati rowset s moznostmi k otazce
     *
     */
    public function getOptions()
    {
        return $this->findDependentRowset('Options');
    }

    /**
     * Vrací true pokud existují odpovědi k této otázce.
     */
    public function isAnswered()
    {
        $r = My_Model::get('Responses');
        $responses = $r->fetchAll($r->select()->where('id_otazka = ?', $this->getid_otazka()));
        return $responses->count() > 0;
    }
}
	