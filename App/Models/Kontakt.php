<?php
class Kontakt {
	private $kontaktID;
	private $klijentID;
	private $ime;
	private $prezime;
	private $adresa;
	private $funkcija;
	private $telefon1;
	private $telefon2;
	private $telefon3;
	private $email;

	public function setKontaktID($kontaktID) {
		$this->kontaktID = $kontaktID;
	}

	public function getKontaktID() {
		return $this->kontaktID;
	}

	public function setKlijentID($klijentID) {
		$this->klijentID = $klijentID;
	}

	public function getKlijentID() {
		return $this->klijentID;
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

	public function setAdresa($adresa) {
		$this->adresa = $adresa;
	}

	public function getAdresa() {
		return $this->adresa;
	}

	public function setFunkcija($funkcija) {
		$this->funkcija = $funkcija;
	}

	public function getFunkcija() {
		return $this->funkcija;
	}

	public function setTelefon1($telefon1) {
		$this->telefon1 = $telefon1;
	}

	public function getTelefon1() {
		return $this->telefon1;
	}

	public function setTelefon2($telefon2) {
		$this->telefon2 = $telefon2;
	}

	public function getTelefon2() {
		return $this->telefon2;
	}

	public function setTelefon3($telefon3) {
		$this->telefon3 = $telefon3;
	}

	public function getTelefon3() {
		return $this->telefon3;
	}

	public function setEmail($email) {
		$this->email = $email;
	}

	public function getEmail() {
		return $this->email;
	}
        
        public function getColumnForComboBox() {
            $columns = array('EntryID'=>'KontaktID', 'EntryName'=>'');
            return $columns;            
        }

	public function getTableName() {
		return 'kontakt';
	}

	public function getAllAttributes() {
		$allAttributes = array('KontaktID'=>trim($this->getKontaktID()), 'KlijentID'=>trim($this->getKlijentID()), 'Ime'=>trim($this->getIme()), 'Prezime'=>trim($this->getPrezime()), 'Adresa'=>trim($this->getAdresa()), 'Funkcija'=>trim($this->getFunkcija()), 'Telefon1'=>trim($this->getTelefon1()), 'Telefon2'=>trim($this->getTelefon2()), 'Telefon3'=>trim($this->getTelefon3()), 'Email'=>trim($this->getEmail()));
		return $allAttributes;
	}

}
?>