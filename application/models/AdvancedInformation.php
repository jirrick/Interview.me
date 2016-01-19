<?php

/**
 * Trida reprezentujici pokročilé informace
 *
 */
class AdvancedInformation extends My_Db_Table_Row {
	
	/**
     * Vrati instanci měny
     *
     */
    public function getCurrency() {
        return $this->findParentRow('Currencies');
    }
	
    /**
     * Vrati instanci úvazku
     *
     */
    public function getContract() {
        return $this->findParentRow('Contracts');
    }

    /**
     * Vrati pole perzonalistů
     *
     */
    public function getInterviewers() {
        return $this->findManyToManyRowset('Users', 'AdvancedInformationsHasInterviewers');
    }	
}
	