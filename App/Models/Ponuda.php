<?php
class Ponuda {
    private $ponudaID;
    private $klijentID;
    private $korisnikID;
    private $sadrzaj;
    private $vrednost;
    private $kampanjaID;

    private $statusPonudaID;
    private $vremePostavke;

    public function setPonudaID($ponudaID) {
        $this->ponudaID = $ponudaID;
    }

    public function getPonudaID() {
        return $this->ponudaID;
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

    public function setSadrzaj($sadrzaj) {
        $this->sadrzaj = $sadrzaj;
    }

    public function getSadrzaj() {
        return $this->sadrzaj;
    }
    
    public function setVrednost($vrednost) {
        $this->vrednost = $vrednost;
    }

    public function getVrednost() {
        return $this->vrednost;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setStatusPonudaID($statusPonudaID) {
        $this->statusPonudaID = $statusPonudaID;
    }

    public function getStatusPonudaID() {
        return $this->statusPonudaID;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }



    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'PonudaID', 'EntryName'=>'');
        return $columns;            
    }

    public function getTableName() {
        return 'ponuda';
    }

    public function getAllAttributes() {
        $allAttributes = array('PonudaID'=>trim($this->getPonudaID()), 'KlijentID'=>trim($this->getKlijentID()), 'KorisnikID'=>trim($this->getKorisnikID()), 'Sadrzaj'=>trim($this->getSadrzaj()), 'Vrednost'=>trim($this->getVrednost()), 'KampanjaID'=>trim($this->getKampanjaID()), 'StatusPonudaID'=>trim($this->getStatusPonudaID()), 'VremePostavke'=>trim($this->getVremePostavke()));
        return $allAttributes;
    }

}
?>
