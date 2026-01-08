<?php

class RadioStanica {

    private $radioStanicaID;
    private $naziv;
    private $adresa;
    private $logo;
    private $aktivan;


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

    public function setAdresa($adresa) {
        $this->adresa = $adresa;
    }

    public function getAdresa() {
        return $this->adresa;
    }

    public function setLogo($logo) {
        $this->logo = $logo;
    }

    public function getLogo() {
        return $this->logo;
    }



    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'radiostanica';
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'RadioStanicaID', 'EntryName' => 'Naziv');
        return $columns;
    }

    public function getAllAttributes() {
        $allAttributes = array('RadioStanicaID' => trim($this->getRadioStanicaID()), 'Naziv' => trim($this->getNaziv()), 'Adresa' => trim($this->getAdresa()), 'Logo' => trim($this->getLogo()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}

?>
