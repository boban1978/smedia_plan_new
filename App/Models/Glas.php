<?php
class Glas {
    private $glasID;
    private $imePrezime;
    private $aktivan;

    public function setGlasID($glasID) {
        $this->glasID = $glasID;
    }

    public function getGlasID() {
        return $this->glasID;
    }

    public function setImePrezime($imePrezime) {
        $this->imePrezime = $imePrezime;
    }

    public function getImePrezime() {
        return $this->imePrezime;
    }

    public function setAktivan($aktivan) {
        $this->aktivan = $aktivan;
    }

    public function getAktivan() {
        return $this->aktivan;
    }

    public function getTableName() {
        return 'glas';
    }
    
    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'GlasID', 'EntryName'=>'ImePrezime');
        return $columns;            
    }

    public function getAllAttributes() {
        $allAttributes = array('GlasID'=>trim($this->getGlasID()), 'ImePrezime'=>trim($this->getImePrezime()), 'Aktivan'=>trim($this->getAktivan()));
        return $allAttributes;
    }

}
?>
