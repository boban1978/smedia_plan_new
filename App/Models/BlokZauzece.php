<?php

class BlokZauzece {

    private $blokZauzeceID;
    private $radioStanicaID;
    private $blokID;
    private $datum;
    private $zauzetoSekundi;
    private $preostaloSekundi;
    private $potvrdjenoSekundi;
    private $nepotvrdjenoSekundi;
    private $zauzetaPrva;
    private $zauzetaDruga;
    private $glasIDs;
    private $delatnostIDs;

    public function setBlokZauzeceID($blokZauzeceID) {
        $this->blokZauzeceID = $blokZauzeceID;
    }

    public function getBlokZauzeceID() {
        return $this->blokZauzeceID;
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

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function setZauzetoSekundi($zauzetoSekundi) {
        $this->zauzetoSekundi = $zauzetoSekundi;
    }

    public function getZauzetoSekundi() {
        return $this->zauzetoSekundi;
    }

    public function setPreostaloSekundi($preostaloSekundi) {
        $this->preostaloSekundi = $preostaloSekundi;
    }

    public function getPreostaloSekundi() {
        return $this->preostaloSekundi;
    }

    public function setPotvrdjenoSekundi($potvrdjenoSekundi) {
        $this->potvrdjenoSekundi = $potvrdjenoSekundi;
    }

    public function getPotvrdjenoSekundi() {
        return $this->potvrdjenoSekundi;
    }

    public function setNepotvrdjenoSekundi($nepotvrdjenoSekundi) {
        $this->nepotvrdjenoSekundi = $nepotvrdjenoSekundi;
    }

    public function getNepotvrdjenoSekundi() {
        return $this->nepotvrdjenoSekundi;
    }

    public function setZauzetaPrva($zauzetaPrva) {
        $this->zauzetaPrva = $zauzetaPrva;
    }

    public function getZauzetaPrva() {
        return $this->zauzetaPrva;
    }

    public function setZauzetaDruga($zauzetaDruga) {
        $this->zauzetaDruga = $zauzetaDruga;
    }

    public function getZauzetaDruga() {
        return $this->zauzetaDruga;
    }
    
    public function setGlasIDs($glasIDs) {
    	$this->glasIDs = $glasIDs;
    }
    
    public function getGlasIDs() {
    	return $this->glasIDs;
    }
    
    public function setDelatnostIDs($delatnostIDs) {
    	$this->delatnostIDs = $delatnostIDs;
    }
    
    public function getDelatnostIDs() {
    	return $this->delatnostIDs;
    }

    public function getTableName() {
        return 'blokzauzece';
    }

    public function getAllAttributes() {
        $allAttributes = array('BlokZauzeceID' => trim($this->getBlokZauzeceID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'BlokID' => trim($this->getBlokID()), 'Datum' => trim($this->getDatum()), 'ZauzetoSekundi' => trim($this->getZauzetoSekundi()), 'PreostaloSekundi' => trim($this->getPreostaloSekundi()), 'PotvrdjenoSekundi' => trim($this->getPotvrdjenoSekundi()), 'NepotvrdjenoSekundi' => trim($this->getNepotvrdjenoSekundi()), 'ZauzetaPrva' => trim($this->getZauzetaPrva()), 'ZauzetaDruga' => trim($this->getZauzetaDruga()), 'GlasIDs' => trim($this->getGlasIDs()), 'DelatnostIDs' => trim($this->getDelatnostIDs()));
        return $allAttributes;
    }

}

?>
