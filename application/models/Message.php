<?php

/**
 * Trida reprezentujici kandidata
 *
 */
class Message extends My_Db_Table_Row {
	
	/**
     * Vrati instanci přiřazeného uživatele
     *
     */
    public function getUser() {
        return $this->findParentRow('Users');
    }
}
	