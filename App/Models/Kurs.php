<?php

class Kurs {

    private $kursID;
    private $datum;
    private $vrednost;


    public function setKursID($kursID) {
        $this->kursID = $kursID;
    }

    public function getKursID() {
        return $this->kursID;
    }

    public function setDatum($datum) {
        $this->datum = $datum;
    }

    public function getDatum() {
        return $this->datum;
    }

    public function setVrednost($vrednost) {
        $this->vrednost = $vrednost;
    }

    public function getVrednost() {
        return $this->vrednost;
    }




    public function getColumnForComboBox() {
        $columns = array('EntryID' => 'KursID', 'EntryName' => '');
        return $columns;
    }

    public function getTableName() {
        return 'kurs';
    }

    public function getAllAttributes() {
        $allAttributes = array('KursID' => trim($this->getKursID()), 'Datum' => trim($this->getDatum()), 'Vrednost' => trim($this->getVrednost()));
        return $allAttributes;
    }

}

?>
