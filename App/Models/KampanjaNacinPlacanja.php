<?php
/**
 * Description of StatusKampanja
 *
 * @author n.lekic
 */
class KampanjaNacinPlacanja {
private $kampanjaNacinPlacanjaID;
    private $naziv;
    private $aktivan;

    public function setKampanjaNacinPlacanjaID($kampanjaNacinPlacanjaID) {
        $this->kampanjaNacinPlacanjaID = $kampanjaNacinPlacanjaID;
    }

    public function getKampanjaNacinPlacanjaID() {
        return $this->kampanjaNacinPlacanjaID;
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
        $columns = array('EntryID'=>'KampanjaNacinPlacanjaID', 'EntryName'=>'Naziv');
        return $columns;            
    }

    public function getTableName() {
        return 'kampanjanacinplacanja';
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaNacinPlacanjaID'=>trim($this->getKampanjaNacinPlacanjaID()), 'Naziv'=>trim($this->getNaziv()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }
}

?>
