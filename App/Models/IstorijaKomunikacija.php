<?php
class IstorijaKomunikacija {
    private $istorijaKomunikacijaID;
    private $klijentID;
    private $korisnikID;
    private $tipKomunikacijaID;
    private $datumKomunikacije;
    private $zaveoID;
    private $vremePostavke;
    private $napomena;
    private $prilogNaziv;
    private $prilogLink;

    public function setIstorijaKomunikacijaID($istorijaKomunikacijaID) {
        $this->istorijaKomunikacijaID = $istorijaKomunikacijaID;
    }

    public function getIstorijaKomunikacijaID() {
        return $this->istorijaKomunikacijaID;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }
    
    public function setTipKomunikacijaID($tipKomunikacijaID) {
        $this->tipKomunikacijaID = $tipKomunikacijaID;
    }

    public function getTipKomunikacijaID() {
        return $this->tipKomunikacijaID;
    }


    public function setDatumKomunikacije($datumKomunikacije) {
        $this->datumKomunikacije = $datumKomunikacije;
    }

    public function getDatumKomunikacije() {
        return $this->datumKomunikacije;
    }

    public function setZaveoID($zaveoID) {
        $this->zaveoID = $zaveoID;
    }

    public function getZaveoID() {
        return $this->zaveoID;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }

    public function setNapomena($napomena) {
        $this->napomena = $napomena;
    }

    public function getNapomena() {
        return $this->napomena;
    }

    public function setPrilogNaziv($prilogNaziv) {
        $this->prilogNaziv = $prilogNaziv;
    }

    public function getPrilogNaziv() {
        return $this->prilogNaziv;
    }
    
    
    public function setPrilogLink($prilogLink) {
        $this->prilogLink = $prilogLink;
    }

    public function getPrilogLink() {
        return $this->prilogLink;
    }
//    public function getColumnForComboBox() {
//        $columns = array('EntryID'=>'IstorijaKomunikacijaID', 'EntryName'=>'');
//        return $columns;            
//    }

    public function getTableName() {
        return 'istorijakomunikacija';
    }

    public function getAllAttributes() {
        $allAttributes = array('IstorijaKomunikacijaID'=>trim($this->getIstorijaKomunikacijaID()), 'KlijentID'=>trim($this->getKlijentID()), 'KorisnikID'=>trim($this->getKorisnikID()), 'TipKomunikacijaID'=>trim($this->getTipKomunikacijaID()), 'DatumKomunikacije'=>trim($this->getDatumKomunikacije()), 'ZaveoID'=>trim($this->getZaveoID()), 'VremePostavke'=>trim($this->getVremePostavke()), 'Napomena'=>trim($this->getNapomena()), 'PrilogNaziv'=>trim($this->getPrilogNaziv()), 'PrilogLink'=>trim($this->getPrilogLink()));
        return $allAttributes;
    }

}
?>
