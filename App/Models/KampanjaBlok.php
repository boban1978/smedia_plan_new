<?php

class KampanjaBlok {

    private $kampanjaBlokID;
    private $radioStanicaID;
    private $kampanjaID;
    private $spotID;
    private $blokID;
    private $datum;
    private $redosled;
    private $cenaEmitovanja;
    private $glasID;
    private $delatnostID;

    public function setKampanjaBlokID($kampanjaBlokID) {
        $this->kampanjaBlokID = $kampanjaBlokID;
    }

    public function getKampanjaBlokID() {
        return $this->kampanjaBlokID;
    }
    
        public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setSpotID($spotID) {
        $this->spotID = $spotID;
    }

    public function getSpotID() {
        return $this->spotID;
    }

    public function setBlokID($blokID) {
        $this->blokID = $blokID;
    }

    public function getBlokID() {
        return $this->blokID;
    }

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function setRedosled($redosled) {
        $this->redosled = $redosled;
    }

    public function getRedosled() {
        return $this->redosled;
    }

    public function setCenaEmitovanja($cenaEmitovanja) {
        $this->cenaEmitovanja = $cenaEmitovanja;
    }

    public function getCenaEmitovanja() {
        return $this->cenaEmitovanja;
    }

    public function setGlasID($glasID) {
        $this->glasID = $glasID;
    }

    public function getGlasID() {
        return $this->glasID;
    }

    public function setDelatnostID($delatnostID) {
        $this->delatnostID = $delatnostID;
    }

    public function getDelatnostID() {
        return $this->delatnostID;
    }

    public function getTableName() {
        return 'kampanjablok';
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaBlokID' => trim($this->getKampanjaBlokID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'KampanjaID' => trim($this->getKampanjaID()), 'SpotID' => trim($this->getSpotID()), 'BlokID' => trim($this->getBlokID()), 'Datum' => trim($this->getDatum()), 'Redosled' => trim($this->getRedosled()), 'CenaEmitovanja' => trim($this->getCenaEmitovanja()), 'GlasID' => trim($this->getGlasID()), 'DelatnostID' => trim($this->getDelatnostID()));
        return $allAttributes;
    }

}

?>
