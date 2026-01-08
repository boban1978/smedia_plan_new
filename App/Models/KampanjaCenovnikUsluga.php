<?php
class KampanjaCenovnikUsluga {
    private $kampanjaCenovnikUslugaID;
    private $kampanjaID;
    private $cenovnikUslugaID;

    public function setKampanjaCenovnikUslugaID($kampanjaCenovnikUslugaID) {
        $this->kampanjaCenovnikUslugaID = $kampanjaCenovnikUslugaID;
    }

    public function getKampanjaCenovnikUslugaID() {
        return $this->kampanjaCenovnikUslugaID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setCenovnikUslugaID($cenovnikUslugaID) {
        $this->cenovnikUslugaID = $cenovnikUslugaID;
    }

    public function getCenovnikUslugaID() {
        return $this->cenovnikUslugaID;
    }
    

    public function getTableName() {
        return 'kampanjacenovnikusluga';
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaCenovnikUslugaID'=>trim($this->getKampanjaCenovnikUslugaID()), 'KampanjaID'=>trim($this->getKampanjaID()), 'CenovnikUslugaID'=>trim($this->getCenovnikUslugaID()));
        return $allAttributes;
    }

}
?>
