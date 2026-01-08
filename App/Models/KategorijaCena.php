<?php
class KategorijaCena {
    private $kategorijaCenaID;
    private $naziv;
    private $trajanjeOd;
    private $trajanjeDo;
    private $aktivan;

    public function setKategorijaCenaID($kategorijaCenaID) {
        $this->kategorijaCenaID = $kategorijaCenaID;
    }

    public function getKategorijaCenaID() {
        return $this->kategorijaCenaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setTrajanjeOd($trajanjeOd) {
        $this->trajanjeOd = $trajanjeOd;
    }

    public function getTrajanjeOd() {
        return $this->trajanjeOd;
    }

    public function setTrajanjeDo($trajanjeDo) {
        $this->trajanjeDo = $trajanjeDo;
    }

    public function getTrajanjeDo() {
        return $this->trajanjeDo;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'kategorijacena';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'KategorijaCenaID', 'EntryName'=>'Naziv');
        //$conndition = "AKTIVAN = 1";
        return $columns;            
    }


    public function getAllAttributes() {
        $allAttributes = array('KategorijaCenaID'=>trim($this->getKategorijaCenaID()), 'Naziv'=>trim($this->getNaziv()), 'TrajanjeOd'=>trim($this->getTrajanjeOd()), 'TrajanjeDo'=>trim($this->getTrajanjeDo()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
