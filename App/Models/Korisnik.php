<?php
class Korisnik {
	private $korisnikID;
	private $ime;
	private $prezime;
	private $username;
	private $password;
	private $adresa;
	private $telefonFix;
	private $telefonMob;
	private $email;
	private $agencijaID;
	private $klijentID;
	private $flagKlijent;
	private $flagAgencija;
	private $flagKuca;
	private $funkcijaID;
        private $roleList;
	private $aktivan;

	public function setKorisnikID($korisnikID) {
		$this->korisnikID = ($korisnikID == -1)? 0:$korisnikID;
	}

	public function getKorisnikID() {
		return $this->korisnikID;
	}

	public function setIme($ime) {
		$this->ime = $ime;
	}

	public function getIme() {
		return $this->ime;
	}

	public function setPrezime($prezime) {
		$this->prezime = $prezime;
	}

	public function getPrezime() {
		return $this->prezime;
	}

	public function setUsername($username) {
		$this->username = $username;
	}

	public function getUsername() {
		return $this->username;
	}

	public function setPassword($password) {
		$this->password = $password;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setAdresa($adresa) {
		$this->adresa = $adresa;
	}

	public function getAdresa() {
		return $this->adresa;
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

	public function setAgencijaID($agencijaID) {
		$this->agencijaID =  $agencijaID;
	}

	public function getAgencijaID() {
		return ($this->agencijaID == '') ? null : $this->agencijaID;
	}

	public function setKlijentID($klijentID) {
		$this->klijentID = $klijentID;
	}

	public function getKlijentID() {
		return ($this->klijentID == '') ? null : $this->klijentID;
	}

	public function setFlagKlijent($flagKlijent) {
		//$this->flagKlijent = ($flagKlijent == 1) ? 1: 0;
        $this->flagKlijent = $flagKlijent;
	}

	public function getFlagKlijent() {
		return $this->flagKlijent;
	}

	public function setFlagAgencija($flagAgencija) {
		//$this->flagAgencija = ($flagAgencija == 1) ? 1: 0;
        $this->flagAgencija = $flagAgencija;
	}

	public function getFlagAgencija() {
		return $this->flagAgencija;
	}

	public function setFlagKuca($flagKuca) {
		//$this->flagKuca = ($flagKuca == 1) ? 1: 0;
        $this->flagKuca = $flagKuca;
	}

	public function getFlagKuca() {
		return $this->flagKuca;
	}

	public function setFunkcijaID($funkcijaID) {
		$this->funkcijaID = $funkcijaID;
	}

	public function getFunkcijaID() {
		return $this->funkcijaID;
	}
        
        public function setRoleList($roleList) {
		$this->roleList = $roleList;
	}

	public function getRoleList() {
		return $this->roleList;
	}

	public function setAktivan($aktivan) {
		$this->aktivan = $aktivan;
	}

	public function getAktivan() {
		return $this->aktivan;
	}

	public function getTableName() {
		return 'korisnik';
	}

	public function getAllAttributes() {
		$allAttributes = array('KorisnikID'=>trim($this->getKorisnikID()), 'Ime'=>trim($this->getIme()), 'Prezime'=>trim($this->getPrezime()), 'Username'=>trim($this->getUsername()), 'Password'=>trim($this->getPassword()), 'Adresa'=>trim($this->getAdresa()), 'TelefonFix'=>trim($this->getTelefonFix()), 'TelefonMob'=>trim($this->getTelefonMob()), 'Email'=>trim($this->getEmail()), 'AgencijaID'=>trim($this->getAgencijaID()), 'KlijentID'=>trim($this->getKlijentID()), 'FlagKlijent'=>trim($this->getFlagKlijent()), 'FlagAgencija'=>trim($this->getFlagAgencija()), 'FlagKuca'=>trim($this->getFlagKuca()), 'FunkcijaID'=>trim($this->getFunkcijaID()), 'Aktivan'=>trim($this->getAktivan()));
		return $allAttributes;
	}

}
?>