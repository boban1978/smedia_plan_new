<?php
class TipPlacanja {
    private $tipPlacanjaID;
    private $naziv;
    private $aktivan;

    public function setTipPlacanjaID($tipPlacanjaID) {
        $this->tipPlacanjaID = $tipPlacanjaID;
    }

    public function getTipPlacanjaID() {
        return $this->tipPlacanjaID;
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
        $columns = array('EntryID'=>'TipPlacanjaID', 'EntryName'=>'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;            
    }

    public function getTableName() {
        return 'tipplacanja';
    }

    public function getAllAttributes() {
        $allAttributes = array('TipPlacanjaID'=>trim($this->getTipPlacanjaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
