<?php

/**
 * Description of KampanjaZahtev
 *
 * @author n.lekic
 */
class KampanjaZahtev {

    private $kampanjaZahtevID;
    private $kampanjaID;
    private $radioStanicaID;
    private $delatnostID;
    private $brendID;
    //Niz spotova
    private $spotArray = array();
    private $naziv;
    private $klijentID;
    private $agencijaID;
    private $korisnikID;
    private $datumPocetka;
    private $datumKraja;
    //private $ucestalost;
    private $budzet;//ako je iz sablona onda je budzet 0 !!!

    private $napomena;

    private $popust;//ako je iz sablona onda postoji popust !!!

    //private $spotTrajanje;
    //private $spotName;
    //private $spotLink;
    //private $dani;
    //private $periodi;
    //private $daniZaEmitovanje;
    //private $periodiDanaZaEmitovanje;
    private $vremePostavke;

    private $tipPlacanjaID;

    //private $premiumBlokovi;

    public function setKampanjaZahtevID($kampanjaZahtevID) {
        $this->kampanjaZahtevID = $kampanjaZahtevID;
    }

    public function getKampanjaZahtevID() {
        return $this->kampanjaZahtevID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setDelatnostID($delatnostID) {
        $this->delatnostID = $delatnostID;
    }

    public function getDelatnostID() {
        return $this->delatnostID;
    }
    
    public function setBrendID($brendID) {
        $this->brendID = $brendID;
    }

    public function getBrendID() {
        return $this->brendID;
    }

    public function setSpotArray($spotArray) {
        $this->spotArray = $spotArray;
    }

    public function getSpotArray() {
        return $this->spotArray;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function setAgencijaID($agencijaID) {
        $this->agencijaID = $agencijaID;
    }

    public function getAgencijaID() {
        return $this->agencijaID;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setDatumPocetka($datumPocetka) {
        $this->datumPocetka = $datumPocetka;
    }

    public function getDatumPocetka() {
        return $this->datumPocetka;
    }

    public function setDatumKraja($datumKraja) {
        $this->datumKraja = $datumKraja;
    }

    public function getDatumKraja() {
        return $this->datumKraja;
    }

//    public function setUcestalost($ucestalost) {
//        $this->ucestalost = $ucestalost;
//    }
//
//    public function getUcestalost() {
//        return $this->ucestalost;
//    }

    public function setBudzet($budzet) {
        $this->budzet = $budzet;
    }

    public function getBudzet() {
        return $this->budzet;
    }


    public function setNapomena($napomena) {
        $this->napomena = $napomena;
    }

    public function getNapomena() {
        return $this->napomena;
    }



    public function setPopust($popust) {
        $this->popust = $popust;
    }

    public function getPopust() {
        return $this->popust;
    }



//    public function setSpotTrajanje($spotTrajanje) {
//        $this->spotTrajanje = $spotTrajanje;
//    }
//
//    public function getSpotTrajanje() {
//        return $this->spotTrajanje;
//    }
//
//    public function setSpotName($spotName) {
//        $this->spotName = $spotName;
//    }
//
//    public function getSpotName() {
//        return $this->spotName;
//    }
//
//    public function setSpotLink($spotLink) {
//        $this->spotLink = $spotLink;
//    }
//
//    public function getSpotLink() {
//        return $this->spotLink;
//    }
//    public function setDani($dani) {
//        $this->dani = $dani;
//    }
//
//    public function getDani() {
//        return $this->dani;
//    }
//
//    public function setPeriodi($periodi) {
//        $this->periodi = $periodi;
//    }
//
//    public function getPeriodi() {
//        return $this->periodi;
//    }
//
//    public function setDaniZaEmitovanje($daniZaEmitovanje) {
//        $this->daniZaEmitovanje = $daniZaEmitovanje;
//    }
//
//    public function getDaniZaEmitovanje() {
//        return $this->daniZaEmitovanje;
//    }
//
//    public function setPeriodiDanaZaEmitovanje($periodiDanaZaEmitovanje) {
//        $this->periodiDanaZaEmitovanje = $periodiDanaZaEmitovanje;
//    }
//
//    public function getPeriodiDanaZaEmitovanje() {
//        return $this->periodiDanaZaEmitovanje;
//    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }



    public function setTipPlacanjaID($tipPlacanjaID) {
        $this->tipPlacanjaID = $tipPlacanjaID;
    }

    public function getTipPlacanjaID() {
        return $this->tipPlacanjaID;
    }




//    public function setPremiumBlokovi($premiumBlokovi) {
//        $this->premiumBlokovi = $premiumBlokovi;
//    }
//
//    public function getPremiumBlokovi() {
//        return $this->premiumBlokovi;
//    }

    public function getTableName() {
        return 'kampanjazahtev';
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaZahtevID' => trim($this->getKampanjaZahtevID()), 'KampanjaID' => trim($this->getKampanjaID()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'Naziv' => trim($this->getNaziv()), 'KlijentID' => trim($this->getKlijentID()), 'AgencijaID' => trim($this->getAgencijaID()), 'KorisnikID' => trim($this->getKorisnikID()), 'DatumPocetka' => trim($this->getDatumPocetka()), 'DatumKraja' => trim($this->getDatumKraja()), /* 'Ucestalost' => trim($this->getUcestalost()), */ 'Budzet' => trim($this->getBudzet()), 'Napomena' => trim($this->getNapomena()), 'Popust' => trim($this->getPopust()), /* 'SpotTrajanje' => trim($this->getSpotTrajanje()), 'SpotName' => trim($this->getSpotName()), 'SpotLink' => trim($this->getSpotLink()), 'DaniZaEmitovanje' => trim($this->getDaniZaEmitovanje()), 'PeriodiDanaZaEmitovanje' => trim($this->getPeriodiDanaZaEmitovanje()), */ 'VremePostavke' => trim($this->getVremePostavke()),/* , 'PremiumBlokovi' => trim($this->getPremiumBlokovi()) */  'TipPlacanjaID' => trim($this->getTipPlacanjaID())  );
        return $allAttributes;
    }

}

?>
