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
     * Vrati instanci seniority
     *
     */
    public function getSeniority() {
        return $this->findParentRow('Seniorities');
    }

    /**
     * Vrati instanci Position
     *
     */
    public function getPosition() {
        return $this->findParentRow('Positions');
    }

    /**
     * Vrati instanci Photo
     *
     */
    public function getFoto() {
        return $this->findParentRow('Photos');
    }

    /**
     * Vrati instanci Attachment
     *
     */
    public function getAttachments() {
        return $this->findManyToManyRowset('Attachments', 'CandidatesHasAttachments');
    }

    /**
     * Vrati seznam technologii
     *
     */
    public function getTechnologies() {
        return $this->findManyToManyRowset('Technologies', 'CandidatesHasTechnologies');
    }
}
	