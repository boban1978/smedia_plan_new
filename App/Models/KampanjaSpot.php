<?php
class KampanjaSpot {

    private $kampanjaSpotID;
    private $kampanjaID;
    private $spotID;

    public function setKampanjaSpotID($kampanjaSpotID) {
        $this->kampanjaSpotID = $kampanjaSpotID;
    }

    public function getKampanjaSpotID() {
        return $this->kampanjaSpotID;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaID = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaID;
    }

    public function setSpotID($spotID) {
        $this->spotID = $spotID;
    }

    public function getSpotID() {
        return $this->spotID;
    }

    
    public function getTableName() {
        return 'kampanjaspot';
    }

    public function getAllAttributes() {
        $allAttributes = array('KampanjaSpotID' => trim($this->getKampanjaSpotID()), 'KampanjaID' => trim($this->getKampanjaID()), 'SpotID' => trim($this->getSpotID()));
        return $allAttributes;
    }

}

?>
