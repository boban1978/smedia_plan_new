<?php
class AgencijaKlijent {
    private $agencijaKlijentID;
    private $agencijaID;
    private $klijentID;
    private $aktivan;

    public function setAgencijaKlijentID($agencijaKlijentID) {
        $this->agencijaKlijentID = $agencijaKlijentID;
    }

    public function getAgencijaKlijentID() {
        return $this->agencijaKlijentID;
    }

    public function setAgencijaID($agencijaID) {
        $this->agencijaID = $agencijaID;
    }

    public function getAgencijaID() {
        return $this->agencijaID;
    }

    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'agencijaklijent';
    }

    public function getAllAttributes() {
        $allAttributes = array('AgencijaKlijentID'=>trim($this->getAgencijaKlijentID()), 'AgencijaID'=>trim($this->getAgencijaID()), 'KlijentID'=>trim($this->getKlijentID()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
