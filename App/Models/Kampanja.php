<?php

class Kampanja {

    private $kampanjaID;
    private $naziv;
    private $radioStanicaID;
    private $klijentID;
    private $datumPocetka;
    private $datumKraja;
    //private $ucestalost;
    private $buzdet;
    //private $spotTrajanje; // Duzina trajanja spota vezanog za kampanju
    //private $dani; // Dani u nedelji u kojima ce se emitovati kampanja 
    //private $periodDana; //Periodi u toku dana u kojima treba emitovati spot
    private $finansijskiStatusID;
    private $statusKampanjaID;
    private $agencijaID;
    private $korisnikID;
    private $prilogIzjava;
    private $ukupnoSekundi;
    private $gratisSekunde;
    private $cenaUkupno;
    private $cenaKonacno;
    private $delatnostID;
    private $brendID;
    private $kampanjaNacinPlacanjaID;
    //private $spotUkupno;
    private $redosledUBloku;
    private $vremeZaPotvrdu;
    private $vremePostavke;
    private $vremePotvrde;
    private $korisnikPotvrdaID;
    private $komentarPotvrda;
    private $ponudaId;
    private $sablonId;
    private $popust;

    private $tipPlacanjaID;

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setRadioStanicaID($radioStanicaID) {
        $this->radioStanicaID = $radioStanicaID;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaID;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
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

    public function setFinansijskiStatusID($finansijskiStatusID) {
        $this->finansijskiStatusID = $finansijskiStatusID;
    }

    public function getFinansijskiStatusID() {
        return $this->finansijskiStatusID;
    }

    public function setStatusKampanjaID($statusKampanjaID) {
        $this->statusKampanjaID = $statusKampanjaID;
    }

    public function getStatusKampanjaID() {
        return $this->statusKampanjaID;
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

    public function setPrilogIzjava($prilogIzjava) {
        $this->prilogIzjava = $prilogIzjava;
    }

    public function getPrilogIzjava() {
        return $this->prilogIzjava;
    }

    public function setUkupnoSekundi($ukupnoSekundi) {
        $this->ukupnoSekundi = $ukupnoSekundi;
    }

    public function getUkupnoSekundi() {
        return $this->ukupnoSekundi;
    }

    public function setGratisSekunde($gratisSekunde) {
        $this->gratisSekunde = $gratisSekunde;
    }

    public function getGratisSekunde() {
        return $this->gratisSekunde;
    }

    public function setCenaUkupno($cenaUkupno) {
        $this->cenaUkupno = $cenaUkupno;
    }

    public function getCenaUkupno() {
        return $this->cenaUkupno;
    }

    public function setBudzet($buzdet) {
        $this->buzdet = $buzdet;
    }

    public function getBudzet() {
        return $this->buzdet;
    }

    public function setCenaKonacno($cenaKonacno) {
        $this->cenaKonacno = $cenaKonacno;
    }

    public function getCenaKonacno() {
        return $this->cenaKonacno;
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

    public function setKampanjaNacinPlacanjaID($kampanjaNacinPlacanjaID) {
        $this->kampanjaNacinPlacanjaID = $kampanjaNacinPlacanjaID;
    }

    public function getKampanjaNacinPlacanjaID() {
        return $this->kampanjaNacinPlacanjaID;
    }

//    public function setSpotUkupno($spotUkupno) {
//        $this->spotUkupno = $spotUkupno;
//    }
//
//    public function getSpotUkupno() {
//        return $this->spotUkupno;
//    }
//    public function setRedosledUBloku($redosledUBloku) {
//        $this->redosledUBloku = $redosledUBloku;
//    }
//
//    public function getRedosledUBloku() {
//        return $this->redosledUBloku;
//    }

    public function setVremeZaPotvrdu($vremeZaPotvrdu) {
        $this->vremeZaPotvrdu = $vremeZaPotvrdu;
    }

    public function getVremeZaPotvrdu() {
        return $this->vremeZaPotvrdu;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }

    public function setVremePotvrde($vremePotvrde) {
        $this->vremePotvrde = $vremePotvrde;
    }

    public function getVremePotvrde() {
        return $this->vremePotvrde;
    }

    public function setKorisnikPotvrdaID($korisnikPotvrdaID) {
        $this->korisnikPotvrdaID = $korisnikPotvrdaID;
    }

    public function getKorisnikPotvrdaID() {
        return $this->korisnikPotvrdaID;
    }

    public function setKomentarPotvrda($komentarPotvrda) {
        $this->komentarPotvrda = $komentarPotvrda;
    }

    public function getKomentarPotvrda() {
        return $this->komentarPotvrda;
    }

    public function setPonudaID($ponudaID) {
        $this->ponudaId = $ponudaID;
    }

    public function getPonudaID() {
        return $this->ponudaId;
    }

    public function setSablonID($sablonID) {
        $this->sablonId = $sablonID;
    }

    public function getSablonID() {
        return $this->sablonId;
    }


    public function setPopust($popust) {
        $this->popust = $popust;
    }

    public function getPopust() {
        return $this->popust;
    }



    public function setTipPlacanjaID($tipPlacanjaID) {
        $this->tipPlacanjaID = $tipPlacanjaID;
    }

    public function getTipPlacanjaID() {
        return $this->tipPlacanjaID;
    }



    public function getTableName() {
        return 'kampanja';
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'KampanjaID', 'EntryName' => 'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaID' => trim($this->getKampanjaID()), 'Naziv' => trim($this->getNaziv()), 'RadioStanicaID' => trim($this->getRadioStanicaID()), 'KlijentID' => trim($this->getKlijentID()), 'DatumPocetka' => trim($this->getDatumPocetka()), 'DatumKraja' => trim($this->getDatumKraja()), 'FinansijskiStatusID' => trim($this->getFinansijskiStatusID()), 'StatusKampanjaID' => trim($this->getStatusKampanjaID()), 'AgencijaID' => trim($this->getAgencijaID()), 'KorisnikID' => trim($this->getKorisnikID()), 'PrilogIzjava' => trim($this->getPrilogIzjava()), 'UkupnoSekundi' => trim($this->getUkupnoSekundi()), 'GratisSekunde' => trim($this->getGratisSekunde()), 'CenaUkupno' => trim($this->getCenaUkupno()), 'Budzet' => trim($this->getBudzet()), 'CenaKonacno' => trim($this->getCenaKonacno()), 'DelatnostID' => trim($this->getDelatnostID()), 'BrendID' => trim($this->getBrendID()), 'VremeZaPotvrdu' => trim($this->getVremeZaPotvrdu()), 'VremePostavke' => trim($this->getVremePostavke()), 'VremePotvrde' => trim($this->getVremePotvrde()), 'KorisnikPotvrdaID' => trim($this->getKorisnikPotvrdaID()), 'KomentarPotvrda' => trim($this->getKomentarPotvrda()), 'PonudaID' => trim($this->getPonudaID()), 'SablonID' => trim($this->getSablonID()), 'Popust' => trim($this->getPopust()), 'TipPlacanjaID' => trim($this->getTipPlacanjaID())  );
        return $allAttributes;
    }

}

?>
