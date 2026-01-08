<?php
class PonudaIstorija {
    private $ponudaIstorijaID;
    private $ponudaID;
    private $statusPonudaID;
    private $napomena;
    private $mediaPlan;
    private $korisnikID;
    private $vremeUnosa;

    public function setPonudaIstorijaID($ponudaIstorijaID) {
        $this->ponudaIstorijaID = $ponudaIstorijaID;
    }

    public function getPonudaIstorijaID() {
        return $this->ponudaIstorijaID;
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

    public function setNapomena($napomena) {
        $this->napomena = $napomena;
    }

    public function getNapomena() {
        return $this->napomena;
    }

    public function setMediaPlan($mediaPlan) {
        $this->mediaPlan = $mediaPlan;
    }

    public function getMediaPlan() {
        return $this->mediaPlan;
    }


    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setVremeUnosa($vremeUnosa) {
        $this->vremeUnosa = $vremeUnosa;
    }

    public function getVremeUnosa() {
        return $this->vremeUnosa;
    }

    public function getTableName() {
        return 'ponudaistorija';
    }

    public function getAllAttributes() {
        $allAttributes = array('PonudaIstorijaID'=>trim($this->getPonudaIstorijaID()), 'PonudaID'=>trim($this->getPonudaID()), 'StatusPonudaID'=>trim($this->getStatusPonudaID()), 'Napomena'=>trim($this->getNapomena()), 'MediaPlan'=>trim($this->getMediaPlan()), 'KorisnikID'=>trim($this->getKorisnikID()), 'VremeUnosa'=>trim($this->getVremeUnosa()));
        return $allAttributes;
    }

}
?>
