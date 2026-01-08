<?php
/**
 * Description of StatusKampanja
 *
 * @author n.lekic
 */
class StatusKampanja {
private $statusKampanjaID;
    private $naziv;
    private $aktivan;

    public function setStatusKampanjaID($statusKampanjaID) {
        $this->statusKampanjaID = $statusKampanjaID;
    }

    public function getStatusKampanjaID() {
        return $this->statusKampanjaID;
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
        $columns = array('EntryID'=>'StatusKampanjaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'statuskampanja';
    }

    public function getAllAttributes() {
        $allAttributes = array('StatusKampanjaID'=>trim($this->getStatusKampanjaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }
}

?>
