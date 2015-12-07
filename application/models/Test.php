<?php

/**
 * Trida reprezentujici sablonu testu
 *
 */
class Test extends My_Db_Table_Row {
	
	/**
     * Vrati instanci technologie
     *
     */
    public function getTechnology() {
        return $this->findParentRow('Technologies');
    }
    
    /**
     * Vrati instanci seniority
     *
     */
    public function getSeniority() {
        return $this->findParentRow('Seniorities');
    }
    
    /**
     * Vrati instanci uzivatele-tvurce
     *
     */
    public function getCreator() {
        return $this->findParentRow('Users');
    }
    
    /**
     * Prepocita pocet otazek
     *
     */
    public function updateQuestionsCount() {
        $rowCount = count($this->getQuestions());
        $this->setpocet_otazek($rowCount);
        $this->save();
    }
    
    /**
     * Vrati rowset s otazkami k testu
     *
     */
     public function getQuestions() {
        return $this->findDependentRowset('Questions');
    }
}
	