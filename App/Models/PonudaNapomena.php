<?php
class PonudaNapomena {
    private $ponudaNapomenaID;
    private $ponudaID;
    private $statusPonudaID;
    private $korisnikID;
    private $napomena;
    private $vremePostavke;

    public function setPonudaNapomenaID($ponudaNapomenaID) {
        $this->ponudaNapomenaID = $ponudaNapomenaID;
    }

    public function getPonudaNapomenaID() {
        return $this->ponudaNapomenaID;
    }

    public function setPonudaID($ponudaID) {
        $this->ponudaID = $ponudaID;
    }

    public function getPonudaID() {
        return $this->ponudaID;
    }

    public function setStatusPonudaID($statusPonudaID) {
        $this->statusPonudaID = $statusPonudaID;
    }

    public function getStatusPonudaID() {
        return $this->statusPonudaID;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setNapomena($napomena) {
        $this->napomena = $napomena;
    }

    public function getNapomena() {
        return $this->napomena;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }

    public function getTableName() {
        return 'ponudanapomena';
    }

    public function getAllAttributes() {
        $allAttributes = array('PonudaNapomenaID'=>trim($this->getPonudaNapomenaID()), 'PonudaID'=>trim($this->getPonudaID()), 'StatusPonudaID'=>trim($this->getStatusPonudaID()), 'KorisnikID'=>trim($this->getKorisnikID()), 'Napomena'=>trim($this->getNapomena()), 'VremePostavke'=>trim($this->getVremePostavke()));
        return $allAttributes;
    }

}
?>
