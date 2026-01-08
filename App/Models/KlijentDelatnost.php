<?php
class KlijentDelatnost {
    private $klijentDelatnostID;
    private $delatnostID;
    private $klijentID;

    public function setKlijentDelatnostID($klijentDelatnostID) {
        $this->klijentDelatnostID = $klijentDelatnostID;
    }

    public function getKlijentDelatnostID() {
        return $this->klijentDelatnostID;
    }

    public function setDelatnostID($delatnostID) {
        $this->delatnostID = $delatnostID;
    }

    public function getDelatnostID() {
        return $this->delatnostID;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function getTableName() {
        return 'klijentdelatnost';
    }

    public function getAllAttributes() {
        $allAttributes = array('KlijentDelatnostID'=>trim($this->getKlijentDelatnostID()), 'DelatnostID'=>trim($this->getDelatnostID()), 'KlijentID'=>trim($this->getKlijentID()));
        return $allAttributes;
    }

}
?>
