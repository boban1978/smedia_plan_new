<?php
class TipKomunikacija {
    private $tipKomunikacijaID;
    private $naziv;
    private $aktivan;

    public function setTipKomunikacijaID($tipKomunikacijaID) {
        $this->tipKomunikacijaID = $tipKomunikacijaID;
    }

    public function getTipKomunikacijaID() {
        return $this->tipKomunikacijaID;
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
        return 'tipkomunikacija';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'TipKomunikacijaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getAllAttributes() {
        $allAttributes = array('TipKomunikacijaID'=>trim($this->getTipKomunikacijaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
