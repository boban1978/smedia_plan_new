<?php
class Faktura {
    private $fakturaID;
    private $kampanjaID;
    private $dokument;


    public function getFakturaID() {
        return $this->fakturaID;
    }

    public function setFaktruraID($fakturaID) {
        $this->fakturaID = $fakturaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getDokument() {
        return $this->dokument;
    }

    public function setDokument($dokument) {
        $this->dokument = $dokument;
    }





    public function getTableName() {
        return 'faktura';
    }

    public function getAllAttributes() {
        $allAttributes = array('FakturaID'=>trim($this->getFakturaID()), 'KampanjaID'=>trim($this->getKampanjaID()), 'Dokument'=>trim($this->getDokument()));
        return $allAttributes;
    }

}
?>
