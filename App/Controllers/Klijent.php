<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$klijentClass = new KlijentClass();
$klijent = new Klijent();

switch ($parameter) {
    case "KlijentLoad":
        $klijentID = $_POST['entryID'];
        $klijent->setKlijentID($klijentID);
        $response = $klijentClass->$parameter($klijent);
        break;
    case "KlijentInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $klijent->setKlijentID($fieldValues->klijentID);
        $klijent->setNaziv($fieldValues->naziv);
        $klijent->setAdresa($fieldValues->adresa);
        $klijent->setDrzava($fieldValues->drzava);
        $klijent->setEmail($fieldValues->email);
        $klijent->setTelefonMob($fieldValues->telefonMobilni);
        $klijent->setTelefonFix($fieldValues->telefonFiksni);
        $klijent->setPib($fieldValues->pib);
        $klijent->setMaticniBroj($fieldValues->maticni);
        $klijent->setRacun($fieldValues->racun);
        $klijent->setAdresaRacun($fieldValues->adresaZaRacun);
        $klijent->setTipUgovoraID($fieldValues->tipUgovoraID);
        $klijent->setDelatnostID($fieldValues->delatnostID);
        $klijent->setTeritorijaPokrivanja($fieldValues->teritorijaPokrivanja);
        $klijent->setPopust($fieldValues->popust);
        $klijent->setAktivan($fieldValues->aktivan);
        $klijent->setAgencijaList($fieldValues->agencijaList);
        $klijent->setDelatnostList($fieldValues->delatnostList);
        if ($fieldValues->klijentID == -1) {
            $action = "KlijentInsert";
        } else {
            $action = "KlijentUpdate";
        }
        $response = $klijentClass->$action($klijent);
        break;
    case "KlijentDelete":
        $klijentID = $_POST['entryID'];
        $klijent->setKlijentID($klijentID);
        $response = $klijentClass->$parameter($klijent);
        break;
    case "KlijentGetForComboBox":
        $filter = $_POST['filter'];
        $response = $klijentClass->$parameter($klijent, $filter);
        break;
    case "KlijentGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];

        $filterValues = json_decode($filterValues);

        if($korisnik_init['tipKorisnik']==3){
            $filterValues->Agencije=array((string)$korisnik_init['agencijaID']);
        }
        $filter = new FilterKlijent($filterValues, $start, $limit, $sort, $dir, $page);

        $response = $klijentClass->$parameter($filter);
        break;
    case "KlijentAgencijaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $agencijaID = $filterValues->agencijaID;
        $response = $klijentClass->$parameter($agencijaID, $start, $limit, $sort, $dir, $page);
        break;
    case "GetAllAgencijaZaKlijenta":
        $response = $klijentClass->$parameter($klijent);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>