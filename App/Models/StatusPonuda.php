<?php
class StatusPonuda {
    private $statusPonudaID;
    private $naziv;
    private $aktivan;

    public function setStatusPonudaID($statusPonudaID) {
        $this->statusPonudaID = $statusPonudaID;
    }

    public function getStatusPonudaID() {
        return $this->statusPonudaID;
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
        $columns = array('EntryID'=>'StatusPonudaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'statusponuda';
    }

    public function getAllAttributes() {
        $allAttributes = array('StatusPonudaID'=>trim($this->getStatusPonudaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
