<?php
class Delatnost {
    private $delatnostID;
    private $naziv;
    private $aktivan;

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

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'DelatnostID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'delatnost';
    }

    public function getAllAttributes() {
        $allAttributes = array('DelatnostID'=>trim($this->getDelatnostID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
