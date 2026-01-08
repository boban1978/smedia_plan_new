<?php
class Blok {
    private $blokID;
    private $sat;
    private $redniBrojSat;
    private $vrsta;
    private $vremeStart;
    private $vremeEnd;
    private $cena;
    private $cenaStaro;
    private $trajanje;
    private $aktivan;

    public function setBlokID($blokID) {
        $this->blokID = $blokID;
    }

    public function getBlokID() {
        return $this->blokID;
    }

    public function setSat($sat) {
        $this->sat = $sat;
    }

    public function getSat() {
        return $this->sat;
    }

    public function setRedniBrojSat($redniBrojSat) {
        $this->redniBrojSat = $redniBrojSat;
    }

    public function getRedniBrojSat() {
        return $this->redniBrojSat;
    }

    public function setVrsta($vrsta) {
        $this->vrsta = $vrsta;
    }

    public function getVrsta() {
        return $this->vrsta;
    }

    public function setVremeStart($vremeStart) {
        $this->vremeStart = $vremeStart;
    }

    public function getVremeStart() {
        return $this->vremeStart;
    }

    public function setVremeEnd($vremeEnd) {
        $this->vremeEnd = $vremeEnd;
    }

    public function getVremeEnd() {
        return $this->vremeEnd;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCena() {
        return $this->cena;
    }

    public function setCenaStaro($cenaStaro) {
        $this->cenaStaro = $cenaStaro;
    }

    public function getCenaStaro() {
        return $this->cenaStaro;
    }

    public function setTrajanje($trajanje) {
        $this->trajanje = $trajanje;
    }

    public function getTrajanje() {
        return $this->trajanje;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'blok';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'BlokID', 'EntryName'=>'concat_ws(\' \', \'Sat\', Sat, \'Rb\', RedniBrojSat)');
        return $columns;            
    }

    public function getAllAttributes() {
        $allAttributes = array('BlokID'=>trim($this->getBlokID()), 'Sat'=>trim($this->getSat()), 'RedniBrojSat'=>trim($this->getRedniBrojSat()), 'Vrsta'=>trim($this->getVrsta()), 'VremeStart'=>trim($this->getVremeStart()), 'VremeEnd'=>trim($this->getVremeEnd()), 'Cena'=>trim($this->getCena()), 'CenaStaro'=>trim($this->getCenaStaro()), 'Trajanje'=>trim($this->getTrajanje()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
