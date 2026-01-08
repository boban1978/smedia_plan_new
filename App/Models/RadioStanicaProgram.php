<?php

class RadioStanicaProgram {

    private $radioStanicaProgramID;
    private $radioStanicaID;
    private $naziv;
    private $pocetakEmitovanja;
    private $krajEmitovanja;
    private $radniDan;
    private $aktivan;

    public function setRadioStanicaProgramID($radioStanicaProgramID) {
        $this->radioStanicaProgramID = $radioStanicaProgramID;
    }

    public function getRadioStanicaProgramID() {
        return $this->radioStanicaProgramID;
    }

    public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setPocetakEmitovanja($pocetakEmitovanja) {
        $this->pocetakEmitovanja = $pocetakEmitovanja;
    }

    public function getPocetakEmitovanja() {
        return $this->pocetakEmitovanja;
    }

    public function setKrajEmitovanja($krajEmitovanja) {
        $this->krajEmitovanja = $krajEmitovanja;
    }

    public function getKrajEmitovanja() {
        return $this->krajEmitovanja;
    }

    public function setRadniDan($radniDan) {
        $this->radniDan = $radniDan;
    }

    public function getRadniDan() {
        return $this->radniDan;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'radiostanicaprogram';
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'RadioStanicaProgramID', 'EntryName' => 'Naziv');
        return $columns;
    }

    public function getAllAttributes() {
        $allAttributes = array('RadioStanicaProgramID' => trim($this->getRadioStanicaProgramID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'Naziv' => trim($this->getNaziv()), 'PocetakEmitovanja' => trim($this->getPocetakEmitovanja()), 'KrajEmitovanja' => trim($this->getKrajEmitovanja()), 'Aktivan' => trim($this->getAktivan()), 'RadniDan' => trim($this->getRadniDan()));
        return $allAttributes;
    }

}

?>
