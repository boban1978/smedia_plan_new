<?php
class Klijent {
	private $klijentID;
	private $naziv;
	private $adresa;
	private $maticniBroj;
	private $pib;
	private $racun;
	private $telefonFiksni;
	private $telefonMobilni;
	private $email;
	private $drzava;
	private $adresaRacun;
	private $tipUgovoraID;
	private $teritorijaPokrivanja;
	private $delatnostID;
	private $popust;
	private $aktivan;
        private $agencijaList;
        private $delatnostList;

	public function setKlijentID($klijentID) {
		$this->klijentID = $klijentID;
	}

	public function getKlijentID() {
		return $this->klijentID;
	}

	public function setNaziv($naziv) {
		$this->naziv = $naziv;
	}

	public function getNaziv() {
		return $this->naziv;
	}

	public function setAdresa($adresa) {
		$this->adresa = $adresa;
	}

	public function getAdresa() {
		return $this->adresa;
	}

	public function setMaticniBroj($maticniBroj) {
		$this->maticniBroj = $maticniBroj;
	}

	public function getMaticniBroj() {
		return $this->maticniBroj;
	}

	public function setPib($pib) {
		$this->pib = $pib;
	}

	public function getPib() {
		return $this->pib;
	}

	public function setRacun($racun) {
		$this->racun = $racun;
	}

	public function getRacun() {
		return $this->racun;
	}

	public function setTelefonFix($telefonFix) {
		$this->telefonFiksni = $telefonFix;
	}

	public function getTelefonFix() {
		return $this->telefonFiksni;
	}

	public function setTelefonMob($telefonMob) {
		$this->telefonMibilni = $telefonMob;
	}

	public function getTelefonMob() {
		return $this->telefonMibilni;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail() {
		return $this->email;
	}

	public function setDrzava($drzava) {
		$this->drzava = $drzava;
	}

	public function getDrzava() {
		return $this->drzava;
	}

	public function setAdresaRacun($adresaRacun) {
		$this->adresaRacun = $adresaRacun;
	}

	public function getAdresaRacun() {
		return $this->adresaRacun;
	}

	public function setTipUgovoraID($tipUgovoraID) {
		$this->tipUgovoraID = $tipUgovoraID;
	}

	public function getTipUgovoraID() {
		return $this->tipUgovoraID;
	}

	public function setTeritorijaPokrivanja($teritorijaPokrivanja) {
		$this->teritorijaPokrivanja = $teritorijaPokrivanja;
	}

	public function getTeritorijaPokrivanja() {
		return $this->teritorijaPokrivanja;
	}

	public function setDelatnostID($delatnostID) {
		$this->delatnostID = $delatnostID;
	}

	public function getDelatnostID() {
		return $this->delatnostID;
	}

	public function setPopust($popust) {
		$this->popust = $popust;
	}

	public function getPopust() {
		return $this->popust;
	}

	public function setAktivan($aktivan) {
		$this->aktivan = $aktivan;
	}

	public function getAktivan() {
		return $this->aktivan;
	}
        
        public function setAgencijaList($agencijaList) {
		$this->agencijaList = $agencijaList;
	}

	public function getAgencijaList() {
		return $this->agencijaList;
	}
        
        public function setDelatnostList($delatnostList) {
		$this->delatnostList = $delatnostList;
	}

	public function getDelatnostList() {
		return $this->delatnostList;
	}

	public function getTableName() {
		return 'klijent';
	}
        
        public function getColumnForComboBox() {
		$columns = array('EntryID'=>'KlijentID', 'EntryName'=>'Naziv');
                //$conndition = "AKTIVAN = 1";
		return $columns;            
        }

	public function getAllAttributes() {
		$allAttributes = array('KlijentID'=>trim($this->getKlijentID()), 'Naziv'=>trim($this->getNaziv()), 'Adresa'=>trim($this->getAdresa()), 'MaticniBroj'=>trim($this->getMaticniBroj()), 'Pib'=>trim($this->getPib()), 'Racun'=>trim($this->getRacun()), 'TelefonFix'=>trim($this->getTelefonFix()), 'TelefonMob'=>trim($this->getTelefonMob()), 'Email'=>trim($this->getEmail()), 'Drzava'=>trim($this->getDrzava()), 'AdresaRacun'=>trim($this->getAdresaRacun()), 'TipUgovoraID'=>trim($this->getTipUgovoraID()), 'TeritorijaPokrivanja'=>trim($this->getTeritorijaPokrivanja()), 'DelatnostID'=>trim($this->getDelatnostID()), 'Popust'=>trim($this->getPopust()), 'Aktivan'=>trim($this->getAktivan()));
		return $allAttributes;
	}

}
?>