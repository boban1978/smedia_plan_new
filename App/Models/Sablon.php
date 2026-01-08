<?php

/**
 * Description of Sablon
 *
 * @author n.lekic
 */
class Sablon {

    private $sablonID;
    private $radioStanicaID;
    private $naziv;
    private $trajanje;
    private $popust;
    private $korisnikID;
    private $vremePostavka;
    private $daniZaEmitovanje;
    private $ucestalost;
    private $aktivan;

    public function setSablonID($sablonID) {
        $this->sablonID = $sablonID;
    }

    public function getSablonID() {
        return $this->sablonID;
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

    public function setTrajanje($trajanje) {
        $this->trajanje = $trajanje;
    }

    public function getTrajanje() {
        return $this->trajanje;
    }

    public function setPopust($popust) {
        $this->popust = $popust;
    }

    public function getPopust() {
        return $this->popust;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setVremePostavka($vremePostavka) {
        $this->vremePostavka = $vremePostavka;
    }

    public function getVremePostavka() {
        return $this->vremePostavka;
    }

    public function setDaniZaEmitovanje($daniZaEmitovanje) {
        $this->daniZaEmitovanje = $daniZaEmitovanje;
    }

    public function getDaniZaEmitovanje() {
        return $this->daniZaEmitovanje;
    }

    public function setUcestalost($ucestalost) {
        $this->ucestalost = $ucestalost;
    }

    public function getUcestalost() {
        return $this->ucestalost;
    }


    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'SablonID', 'EntryName' => 'Naziv');
        return $columns;
    }

    public function getTableName() {
        return 'sablon';
    }

    public function getAllAttributes() {
        $allAttributes = array('SablonID' => trim($this->getSablonID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'Naziv' => trim($this->getNaziv()), 'Trajanje' => trim($this->getTrajanje()), 'Popust' => trim($this->getPopust()), 'KorisnikID' => trim($this->getKorisnikID()), 'VremePostavka' => trim($this->getVremePostavka()), 'DaniZaEmitovanje' => trim($this->getDaniZaEmitovanje()), 'Ucestalost' => trim($this->getUcestalost()), 'Aktivan' => trim($this->getAktivan()));
        return $allAttributes;
    }

}

?>
