<?php

class SablonSponzorstvo {

    private $sablonSponzorstvoID;
    private $klijentID;
    private $radioStanicaID;
    private $radioStanicaProgramID;
    private $datumOd;
    private $datumDo;
    private $cenaUkupno;
    private $cobrending;
    private $najavaOdjava;
    private $premiumBlok;
    private $prsegment;
    private $najavaEmisije;
    private $korisnikID;
    private $vremePostavke;

    public function setSablonSponzorstvoID($sablonSponzorstvoID) {
        $this->sablonSponzorstvoID = $sablonSponzorstvoID;
    }

    public function getSablonSponzorstvoID() {
        return $this->sablonSponzorstvoID;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setRadioStanicaProgramID($radioStanicaProgramID) {
        $this->radioStanicaProgramID = $radioStanicaProgramID;
    }

    public function getRadioStanicaProgramID() {
        return $this->radioStanicaProgramID;
    }

    public function setDatumOd($datumOd) {
        $this->datumOd = $datumOd;
    }

    public function getDatumOd() {
        return $this->datumOd;
    }

    public function setDatumDo($datumDo) {
        $this->datumDo = $datumDo;
    }

    public function getDatumDo() {
        return $this->datumDo;
    }

    public function setCenaUkupno($cenaUkupno) {
        $this->cenaUkupno = $cenaUkupno;
    }

    public function getCenaUkupno() {
        return $this->cenaUkupno;
    }

    public function setCobrending($cobrending) {
        $this->cobrending = $cobrending;
    }

    public function getCobrending() {
        return $this->cobrending;
    }

    public function setNajavaOdjava($najavaOdjava) {
        $this->najavaOdjava = $najavaOdjava;
    }

    public function getNajavaOdjava() {
        return $this->najavaOdjava;
    }

    public function setNajavaEmisije($najavaEmisije) {
        $this->najavaEmisije = $najavaEmisije;
    }

    public function getNajavaEmisije() {
        return $this->najavaEmisije;
    }

    public function setPrsegment($prsegment) {
        $this->prsegment = $prsegment;
    }

    public function getPrsegment() {
        return $this->prsegment;
    }

    public function setPremiumBlok($premiumBlok) {
        $this->premiumBlok = $premiumBlok;
    }

    public function getPremiumBlok() {
        return $this->premiumBlok;
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

    public function getTableName() {
        return 'sablonsponzorstvo';
    }

    public function getAllAttributes() {
        $allAttributes = array('SablonSponzorstvoID' => trim($this->getSablonSponzorstvoID()),
            'KlijentID' => trim($this->getKlijentID()),
            'RadioStanicaID' => trim($this->getRadioStanicaID()),
            'RadioStanicaProgramID' => trim($this->getRadioStanicaProgramID()),
            'DatumOd' => trim($this->getDatumOd()),
            'DatumDo' => trim($this->getDatumDo()),
            'CenaUkupno' => trim($this->getCenaUkupno()),
            'Cobrending' => trim($this->getCobrending()),
            'NajavaOdjava' => trim($this->getNajavaOdjava()),
            'NajavaEmisije' => trim($this->getNajavaEmisije()),
            'Prsegment' => trim($this->getPrsegment()),
            'PremiumBlok' => trim($this->getPremiumBlok()),
            'KorisnikID' => trim($this->getKorisnikID()),
            'VremePostavke' => trim($this->getVremePostavke()));
        return $allAttributes;
    }

}

?>
