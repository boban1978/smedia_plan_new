<?php

class Spot {

    private $spotID;
    private $kampanjaID;
    private $spotName;
    private $spotLink;
    private $glasID;
    private $spotTrajanje;
    private $ucestalostSuma;
    private $spotUcestalost = array();
    private $daniZaEmitovanje;
    private $periodiZaEmitovanje;
    private $dani;
    private $periodi;
    private $premiumBlokovi;
    private $korisnikID;
    private $vremePostavke;
    private $days = array();
    private $numberDays;

    public function setSpotID($spotID) {
        $this->spotID = $spotID;
    }

    public function getSpotID() {
        return $this->spotID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setSpotName($spotName) {
        $this->spotName = $spotName;
    }

    public function getSpotName() {
        return $this->spotName;
    }

    public function setSpotLink($spotLink) {
        $this->spotLink = $spotLink;
    }

    public function getSpotLink() {
        return $this->spotLink;
    }

    public function setGlasID($glasID) {
        $this->glasID = $glasID;
    }

    public function getGlasID() {
        return $this->glasID;
    }

    public function setSpotTrajanje($trajanje) {
        $this->spotTrajanje = $trajanje;
    }

    public function getSpotTrajanje() {
        return $this->spotTrajanje;
    }

    public function setUcestalostSuma() {
        $this->ucestalostSuma = array_sum($this->spotUcestalost);
    }

    public function getSpotUcestalost() {
        return $this->spotUcestalost;
    }

    public function setSpotUcestalost($spotUcestalostArray) {
        $this->spotUcestalost = $spotUcestalostArray;
    }

    public function getUcestalotSuma() {
        return $this->ucestalostSuma;
    }

    public function setDaniZaEmitovanje($daniZaEmitovanje) {
        $this->daniZaEmitovanje = $daniZaEmitovanje;
    }

    public function getDaniZaEmitovanje() {
        return $this->daniZaEmitovanje;
    }

    public function setPeriodiZaEmitovanje($periodiZaEmitovanje) {
        $this->periodiZaEmitovanje = $periodiZaEmitovanje;
    }

    public function getPeriodiZaEmitovanje() {
        return $this->periodiZaEmitovanje;
    }

    public function setDani($dani) {
        $this->dani = $dani;
    }

    public function getDani() {
        return $this->dani;
    }

    public function setPeriodi($periodi) {
        $this->periodi = $periodi;
    }

    public function getPeriodi() {
        return $this->periodi;
    }

    public function setPremiumBlokovi($premiumBlokovi) {
        $this->premiumBlokovi = $premiumBlokovi;
    }

    public function getPremiumBlokovi() {
        return $this->premiumBlokovi;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }

    public function setNumberDays($numberDays) {
        $this->numberDays = $numberDays;
    }

    public function getNumberDays() {
        return $this->numberDays;
    }

    public function setDays($days) {
        $this->days = $days;
    }

    public function getDays() {
        return $this->days;
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'SpotID', 'EntryName' => '');
        return $columns;
    }

    public function getTableName() {
        return 'spot';
    }

    public function getAllAttributes() {
        $allAttributes = array('SpotID' => trim($this->getSpotID()), 'KampanjaID' => trim($this->getKampanjaID()), 'SpotName' => trim($this->getSpotName()), 'SpotLink' => trim($this->getSpotLink()), 'GlasID' => trim($this->getGlasID()), 'SpotTrajanje' => trim($this->getSpotTrajanje())
            , 'Ucestalost' => trim($this->getUcestalotSuma()), 'DaniZaEmitovanje' => trim($this->getDaniZaEmitovanje()), 'PeriodiZaEmitovanje' => trim($this->getPeriodiZaEmitovanje()), 'PremiumBlokovi' => trim($this->getPremiumBlokovi()), 'KorisnikID' => trim($this->getSpotTrajanje())
            , 'VremePostavke' => trim($this->getVremePostavke()));
        return $allAttributes;
    }

}

?>
