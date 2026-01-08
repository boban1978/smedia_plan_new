<?php
class MediaPlan {
    private $mediaPlanID;
    private $datum;
    private $blokID;
    private $kampanjaID;
    private $spotID;
    private $redniBrojBlok;
    private $cena;
    private $potvrda;
    private $korisnikID;
    private $vremePostavke;

    public function setMediaPlanID($mediaPlanID) {
        $this->mediaPlanID = $mediaPlanID;
    }

    public function getMediaPlanID() {
        return $this->mediaPlanID;
    }

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function setBlokID($blokID) {
        $this->blokID = $blokID;
    }

    public function getBlokID() {
        return $this->blokID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setSpotID($spotID) {
        $this->spotID = $spotID;
    }

    public function getSpotID() {
        return $this->spotID;
    }

    public function setRedniBrojBlok($redniBrojBlok) {
        $this->redniBrojBlok = $redniBrojBlok;
    }

    public function getRedniBrojBlok() {
        return $this->redniBrojBlok;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCena() {
        return $this->cena;
    }

    public function setPotvrda($potvrda) {
        $this->potvrda = $potvrda;
    }

    public function getPotvrda() {
        return $this->potvrda;
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
        return 'mediaplan';
    }

    public function getAllAttributes() {
        $allAttributes = array('MediaPlanID'=>trim($this->getMediaPlanID()), 'Datum'=>trim($this->getDatum()), 'BlokID'=>trim($this->getBlokID()), 'KampanjaID'=>trim($this->getKampanjaID()), 'SpotID'=>trim($this->getSpotID()), 'RedniBrojBlok'=>trim($this->getRedniBrojBlok()), 'Cena'=>trim($this->getCena()), 'Potvrda'=>trim($this->getPotvrda()), 'KorisnikID'=>trim($this->getKorisnikID()), 'VremePostavke'=>trim($this->getVremePostavke()));
        return $allAttributes;
    }

}
?>
