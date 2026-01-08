<?php
class CenovnikUsluga {
    private $cenovnikUslugaID;
    private $naziv;
    private $cena;

    public function setCenovnikUslugaID($cenovnikUslugaID) {
        $this->cenovnikUslugaID = $cenovnikUslugaID;
    }

    public function getCenovnikUslugaID() {
        return $this->cenovnikUslugaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setCena($cena) {
        $this->cena = $cena;
    }

    public function getCena() {
        return $this->cena;
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'CenovnikUslugaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'cenovnikusluga';
    }

    public function getAllAttributes() {
        $allAttributes = array('CenovnikUslugaID'=>trim($this->getCenovnikUslugaID()), 'Naziv'=>trim($this->getNaziv()), 'Cena'=>trim($this->getCena()));
        return $allAttributes;
    }

}
?>
