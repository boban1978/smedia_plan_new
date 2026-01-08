<?php
class KorisnikRola {
    private $korisnikRolaID;
    private $korisnikID;
    private $rolaID;
    private $aktivan;

    public function setKorisnikRolaID($korisnikRolaID) {
        $this->korisnikRolaID = $korisnikRolaID;
    }

    public function getKorisnikRolaID() {
        return $this->korisnikRolaID;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setRolaID($rolaID) {
        $this->rolaID = $rolaID;
    }

    public function getRolaID() {
        return $this->rolaID;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'korisnikrola';
    }

    public function getAllAttributes() {
        $allAttributes = array('KorisnikRolaID'=>trim($this->getKorisnikRolaID()), 'KorisnikID'=>trim($this->getKorisnikID()), 'RolaID'=>trim($this->getRolaID()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
