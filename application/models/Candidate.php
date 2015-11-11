<?php

/**
 * Trida reprezentujici kandidata
 *
 */
class Candidate extends My_Db_Table_Row {
	
	/**
     * Vrati instanci stavu
     *
     */
    public function getStatus() {
        return $this->findParentRow('Statuses');
    }
	
    /**
     * Vrati seznam technologii
     *
     */
    public function getTechnologies() {
        return $this->findManyToManyRowset('Technologies', 'CandidatesHasTechnologies');
    }
    
	
	
	
}
	