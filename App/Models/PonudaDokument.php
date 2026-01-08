<?php
class PonudaDokument {
    private $ponudaDokumentID;
    private $ponudaID;
    private $naziv;
    private $link;

    public function setPonudaDokumentID($ponudaDokumentID) {
        $this->ponudaDokumentID = $ponudaDokumentID;
    }

    public function getPonudaDokumentID() {
        return $this->ponudaDokumentID;
    }

    public function setPonudaID($ponudaID) {
        $this->ponudaID = $ponudaID;
    }

    public function getPonudaID() {
        return $this->ponudaID;
    }

    public function setNaziv($naziv) {
        $this->naziv = $naziv;
    }

    public function getNaziv() {
        return $this->naziv;
    }

    public function setLink($link) {
        $this->link = $link;
    }

    public function getLink() {
        return $this->link;
    }
    
        public function setKorisnikID($korisnikID) {
        $this->korisnikID = $korisnikID;
    }

    public function getKorisnikID() {
        return $this->korisnikID;
    }

    public function setVremePostavke($vremePostavke) {
        $this->vremePostavke = $vremePostavke;
    }

    public function getVremePostavke() {
        return $this->vremePostavke;
    }

    public function getColumnForComboBox() {
        $columns = array('EntryID'=>'PonudaDocumentID', 'EntryName'=>'');
        return $columns;            
    }

    public function getTableName() {
        return 'ponudadokument';
    }

    public function getAllAttributes() {
        $allAttributes = array('PonudaDokumentID'=>trim($this->getPonudaDokumentID()), 'PonudaID'=>trim($this->getPonudaID()), 'Naziv'=>trim($this->getNaziv()), 'Link'=>trim($this->getLink()), 'KorisnikID'=>trim($this->getKorisnikID()), 'VremePostavke'=>trim($this->getVremePostavke()));
        return $allAttributes;
    }

}
?>
