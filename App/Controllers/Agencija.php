<?php
require_once __DIR__.'/../../init.php';
//if(!isset($_SESSION['sess_idkor'])){header("Location: ".HOME_ADDRESS."?message=Morate se ulogovati!");exit;}
$paraGet = $_GET['action'];
$parameter = $_POST['action'];

$agencijaClass = new AgencijaClass();
$agencija = new Agencija();


switch ($parameter) {
    case "AgencijaLoad":
        $agencijaID = $_POST['entryID'];
        $agencija->setAgencijaID($agencijaID);
        $response = $agencijaClass->$parameter($agencija);
        break;
    case "AgencijaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $agencija->setAgencijaID($fieldValues->agencijaID);
        $agencija->setNaziv($fieldValues->naziv);
        $agencija->setAdresa($fieldValues->adresa);
        $agencija->setDrzava($fieldValues->drzava);
        $agencija->setEmail($fieldValues->email);
        $agencija->setKontaktOsoba($fieldValues->kontaktOsoba);
        $agencija->setTelefonMob($fieldValues->telefonMobilni);
        $agencija->setTelefonFix($fieldValues->telefonFiksni);
        $agencija->setPib($fieldValues->pib);
        $agencija->setMaticniBroj($fieldValues->maticni);
        $agencija->setRacun($fieldValues->racun);
        $agencija->setAdresaZaRacun($fieldValues->adresaZaRacun);
        $agencija->setPopust($fieldValues->popust);
        $agencija->setAktivan($fieldValues->aktivan);
        if ($fieldValues->agencijaID == -1) {
            $action = "AgencijaInsert";
        } else {
            $action = "AgencijaUpdate";
        }
        $response = $agencijaClass->$action($agencija);
        break;
    case "AgencijaDelete":
        $agencijaID = $_POST['entryID'];
        $agencija->setAgencijaID($agencijaID);
        $response = $agencijaClass->$parameter($agencija);
        break;
    case "AgencijaGetForComboBox":
        $response = $agencijaClass->$parameter($agencija);
        break;
    case "AgencijaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);




        if($korisnik_init['tipKorisnik']==3){
            $filterValues->agencijaID=$korisnik_init['agencijaID'];
        }


        $filter = new FilterAgencija($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $agencijaClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>