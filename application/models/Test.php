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
     * Vrati pocet otazek
     *
     */
    public function getQuestionsCount() {
        if ($this->getpocet_otazek() === null){
           $rowset = $this->findDependentRowset('Questions');
           $rowCount = count($rowset);
           $this->setpocet_otazek($rowCount);
           $this->save();
        }
        return $this->getpocet_otazek();
    }
}
	