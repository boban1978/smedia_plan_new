<?php
class TipUgovora {
    private $tipUgovoraID;
    private $naziv;
    private $aktivan;

    public function setTipUgovoraID($tipUgovoraID) {
        $this->tipUgovoraID = $tipUgovoraID;
    }

    public function getTipUgovoraID() {
        return $this->tipUgovoraID;
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
        $columns = array('EntryID'=>'TipUgovoraID', 'EntryName'=>'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;            
    }

    public function getTableName() {
        return 'tipugovora';
    }

    public function getAllAttributes() {
        $allAttributes = array('TipUgovoraID'=>trim($this->getTipUgovoraID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
