<?php
class Rola {
    private $rolaID;
    private $naziv;
    private $opis;
    private $aktivan;
    private $permisijaList;

    public function setRolaID($rolaID) {
        $this->rolaID = $rolaID;
    }

    public function getRolaID() {
        return $this->rolaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }
    
    public function setOpis($opis) {
        $this->opis = $opis;
    }

    public function getOpis() {
        return $this->opis;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }
    
    public function setPermisijaList($permisijaList) {
        $this->permisijaList = $permisijaList;
    }

    public function getPermisijaList() {
        return $this->permisijaList;
    }

    public function getTableName() {
        return 'rola';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'RolaID', 'EntryName'=>'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;            
    }

    public function getAllAttributes() {
        $allAttributes = array('RolaID'=>trim($this->getRolaID()), 'Naziv'=>trim($this->getNaziv()), 'Opis'=>trim($this->getOpis()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
