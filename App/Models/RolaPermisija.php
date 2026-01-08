<?php
class RolaPermisija {
    private $rolaPermisijaID;
    private $rolaID;
    private $permisijaID;
    private $aktivan;

    public function setRolaPermisijaID($rolaPermisijaID) {
        $this->rolaPermisijaID = $rolaPermisijaID;
    }

    public function getRolaPermisijaID() {
        return $this->rolaPermisijaID;
    }

    public function setRolaID($rolaID) {
        $this->rolaID = $rolaID;
    }

    public function getRolaID() {
        return $this->rolaID;
    }

    public function setPermisijaID($permisijaID) {
        $this->permisijaID = $permisijaID;
    }

    public function getPermisijaID() {
        return $this->permisijaID;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'rolapermisija';
    }

    public function getAllAttributes() {
        $allAttributes = array('RolaPermisijaID'=>trim($this->getRolaPermisijaID()), 'RolaID'=>trim($this->getRolaID()), 'PermisijaID'=>trim($this->getPermisijaID()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
