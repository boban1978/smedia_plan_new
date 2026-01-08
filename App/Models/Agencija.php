<?php
class Agencija {
	private $agencijaID;
	private $naziv;
	private $adresa;
	private $maticniBroj;
        private $drzava;
        private $adresaZaRacun;
	private $pib;
	private $racun;
	private $kontaktOsoba;
	private $telefonFix;
	private $telefonMob;
	private $email;
	private $popust;
	private $aktivan;

	public function setAgencijaID($agencijaID) {
		$this->agencijaID = $agencijaID;
	}

	public function getAgencijaID() {
		return $this->agencijaID;
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

	public function setKontaktOsoba($kontaktOsoba) {
		$this->kontaktOsoba = $kontaktOsoba;
	}

	public function getKontaktOsoba() {
		return $this->kontaktOsoba;
	}

	public function setTelefonFix($telefonFix) {
		$this->telefonFix = $telefonFix;
	}

	public function getTelefonFix() {
		return $this->telefonFix;
	}

	public function setTelefonMob($telefonMob) {
		$this->telefonMob = $telefonMob;
	}

	public function getTelefonMob() {
		return $this->telefonMob;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail() {
		return $this->email;
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
        
        public function setDrzava($drzava) {
		$this->drzava = $drzava;
	}

	public function getDrzava() {
		return $this->drzava;
	}
        
        public function setAdresaZaRacun($adresaZaRacun) {
		$this->adresaZaRacun = $adresaZaRacun;
	}

	public function getAdresaZaRacun() {
		return $this->adresaZaRacun;
	}

	public function getTableName() {
		return 'agencija';
	}
        
        public function getColumnForComboBox() {
		$columns = array('EntryID'=>'AgencijaID', 'EntryName'=>'Naziv');
                //$conndition = "AKTIVAN = 1";
		return $columns;            
        }

	public function getAllAttributes() {
		$allAttributes = array('AgencijaID'=>trim($this->getAgencijaID()), 'Naziv'=>trim($this->getNaziv()), 'Adresa'=>trim($this->getAdresa()), 'MaticniBroj'=>trim($this->getMaticniBroj()), 'Pib'=>trim($this->getPib()), 'Racun'=>trim($this->getRacun()), 'KontaktOsoba'=>trim($this->getKontaktOsoba()), 'TelefonFix'=>trim($this->getTelefonFix()), 'TelefonMob'=>trim($this->getTelefonMob()), 'Email'=>trim($this->getEmail()), 'Popust'=>trim($this->getPopust()), 'Aktivan'=>trim($this->getAktivan()), 'Drzava'=>trim($this->getDrzava()), 'AdresaZaRacun'=>trim($this->getAdresaZaRacun()));
		return $allAttributes;
	}

}
?>