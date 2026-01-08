<?php

class Cenovnik {

    private $cenovnikID;
    private $radioStanicaID;
    private $blokID;
    private $kategorijaCenaID;
    private $cena;
    private $vikend;
    private $aktivan;

    public function setCenovnikID($cenovnikID) {
        $this->cenovnikID = $cenovnikID;
    }

    public function getCenovnikID() {
        return $this->cenovnikID;
    }

    public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setBlokID($blokID) {
        $this->blokID = $blokID;
    }

    public function getBlokID() {
        return $this->blokID;
    }

    public function setKategorijaCenaID($kategorijaCenaID) {
        $this->kategorijaCenaID = $kategorijaCenaID;
    }

    public function getKategorijaCenaID() {
        return $this->kategorijaCenaID;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCena() {
        return $this->cena;
    }

    public function setVikend($vikend) {
        $this->vikend = $vikend;
    }

    public function getVikend() {
        return $this->vikend;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'cenovnik';
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'CenovnikID', 'EntryName' => 'Cena');
        return $columns;
    }

    public function getAllAttributes() {
        $allAttributes = array('CenovnikID' => trim($this->getCenovnikID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'BlokID' => trim($this->getBlokID()), 'KategorijaCenaID' => trim($this->getKategorijaCenaID()), 'Cena' => trim($this->getCena()), 'Vikend' => trim($this->getVikend()), 'Aktivan' => trim($this->getAktivan()));
        return $allAttributes;
    }

}

?>
