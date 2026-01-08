<?php
/**
 * Description of SablonBlok
 *
 * @author n.lekic
 */
class SablonBlok {
    private $sablonBlokID;
    private $sablonID;
    private $blokID;
    private $datum;
    private $redosled;

    public function setSablonBlokID($sablonBlokID) {
        $this->sablonBlokID = $sablonBlokID;
    }

    public function getSablonBlokID() {
        return $this->sablonBlokID;
    }
    
    public function setSablonID($sablonID) {
        $this->sablonID = $sablonID;
    }

    public function getSablonID() {
        return $this->sablonID;
    }

    public function setBlokID($blokID) {
        $this->blokID = $blokID;
    }

    public function getBlokID() {
        return $this->blokID;
    }

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }
    
    public function setRedosled($redosled) {
        $this->redosled = $redosled;
    }

    public function getRedosled() {
        return $this->redosled;
    }

    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'SablonBlokID', 'EntryName'=>'Datum');
        return $columns;            
    }

    public function getTableName() {
        return 'sablonblok';
    }

    public function getAllAttributes() {
        $allAttributes = array('SablonBlokID'=>trim($this->getSablonBlokID()), 'SablonID'=>trim($this->getSablonID()), 'BlokID'=>trim($this->getBlokID()), 'Datum'=>trim($this->getDatum()), 'Redosled'=>trim($this->getRedosled()));
        return $allAttributes;
    }
}

?>
