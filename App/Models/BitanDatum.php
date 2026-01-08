<?php
class BitanDatum {
    private $bitanDatumID;
    private $ID;
    private $vrsta;
    private $datum;
    private $opis;
    private $aktivan;

    public function setBitanDatumID($bitanDatumID) {
        $this->bitanDatumID = $bitanDatumID;
    }

    public function getBitanDatumID() {
        return $this->bitanDatumID;
    }

    public function setID($ID) {
        $this->ID = $ID;
    }

    public function getID() {
        return $this->ID;
    }

    public function setVrsta($vrsta) {
        $this->vrsta = $vrsta;
    }

    public function getVrsta() {
        return $this->vrsta;
    }

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function setOpis($opis) {
        $this->opis = $opis;
    }

    public function getOpis() {
        return $this->opis;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'bitandatum';
    }

    public function getAllAttributes() {
        $allAttributes = array('BitanDatumID'=>trim($this->getBitanDatumID()), 'ID'=>trim($this->getID()), 'Vrsta'=>trim($this->getVrsta()), 'Datum'=>trim($this->getDatum()), 'Opis'=>trim($this->getOpis()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
