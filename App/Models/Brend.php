<?php
class Brend {
    private $brendID;
    private $naziv;
    private $klijentID;
    private $delatnostID;
    
    public function setBrendID($brendID) {
        $this->brendID = $brendID;
    }

    public function getBrendID() {
        return $this->brendID;
    }
    
    public function setKlijentID($klijentID) {
        $this->klijentID = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentID;
    }

    public function setDelatnostID($delatnostID) {
        $this->delatnostID = $delatnostID;
    }

    public function getDelatnostID() {
        return $this->delatnostID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'BrendID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'brend';
    }

    public function getAllAttributes() {
        $allAttributes = array('BrendID'=>trim($this->getBrendID()), 'KlijentID'=>trim($this->getKlijentID()), 'DelatnostID'=>trim($this->getDelatnostID()), 'Naziv'=>trim($this->getNaziv()));
        return $allAttributes;
    }

}
?>
