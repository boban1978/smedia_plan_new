<?php
class Permisija {
    private $permisijaID;
    private $naziv;
    private $aktivan;

    public function setPermisijaID($permisijaID) {
        $this->permisijaID = $permisijaID;
    }

    public function getPermisijaID() {
        return $this->permisijaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'permisija';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'PermisijaID', 'EntryName'=>'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;            
    }
    
    public function getAllAttributes() {
        $allAttributes = array('PermisijaID'=>trim($this->getPermisijaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
