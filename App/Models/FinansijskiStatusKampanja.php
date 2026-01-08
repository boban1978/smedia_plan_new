<?php
class FinansijskiStatusKampanja {
    private $finansijskiStatusKampanjaID;
    private $naziv;
    private $aktivan;

    public function setFinansijskiStatusKampanjaID($finansijskiStatusKampanjaID) {
        $this->finansijskiStatusKampanjaID = $finansijskiStatusKampanjaID;
    }

    public function getFinansijskiStatusKampanjaID() {
        return $this->finansijskiStatusKampanjaID;
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
        $columns = array('EntryID'=>'FinansijskiStatusKampanjaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'finansijskistatuskampanja';
    }

    public function getAllAttributes() {
        $allAttributes = array('FinansijskiStatusKampanjaID'=>trim($this->getFinansijskiStatusKampanjaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
