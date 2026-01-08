<?php
class Log {
    private $logID;
    private $korisnikID;
    private $vremeLog;

    public function setLogID($logID) {
        $this->logID = $logID;
    }

    public function getLogID() {
        return $this->logID;
    }

    public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setVremeLog($vremeLog) {
        $this->vremeLog = $vremeLog;
    }

    public function getVremeLog() {
        return $this->vremeLog;
    }

    public function getTableName() {
        return 'log';
    }
    

    public function getAllAttributes() {
        $allAttributes = array('LogID'=>trim($this->getLogID()), 'KorisnikID'=>trim($this->getKorisnikID()), 'VremeLog'=>trim($this->getVremeLog()));
        return $allAttributes;
    }

}
?>

