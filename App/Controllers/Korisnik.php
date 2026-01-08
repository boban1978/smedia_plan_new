<?php

require_once __DIR__.'/../../init.php';

//include_once '../../Config/Enum.php';

//$paraGet = $_GET['action'];
$parameter = $_POST['action'];

//$korisnik = new Korisnik();
//$response = new CoreAjaxResponseInfo();
$korisnikClass = new KorisnikClass();
$korisnik = new Korisnik();



/*
        $result = $_POST;
        $responseNew = new CoreAjaxResponseInfo();
        $responseNew->SetSuccess('true');
        $responseNew->SetData('data:'.json_encode($result));
        ob_clean();
        header('Content-type: application/json');
        echo $responseNew->GetResponse();
        ob_flush();
        exit;
*/


switch ($parameter) {
    case "KorisnikLoad":       
        $entryID = $_POST['entryID'];
        $korisnik->setKorisnikID($entryID);
        $response = $korisnikClass->$parameter($korisnik);
        break;
    case "KorisnikDetailsLoad":       
        $entryID = $_SESSION['sess_idkor'];
        $korisnik->setKorisnikID($entryID);
        $response = $korisnikClass->$parameter($korisnik);
        break;
    case "KorisnikImePrezimeGet":       
        $entryID = $_SESSION['sess_idkor'];
        $korisnik->setKorisnikID($entryID);
        $response = $korisnikClass->$parameter($korisnik);
        break;
    case "KomercijalistaGetList":
        $response = $korisnikClass->$parameter();
        break;
    case "KorisnikInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);

        $korisnik->setKorisnikID($fieldValues->korisnikID);
        $korisnik->setIme($fieldValues->ime);
        $korisnik->setPrezime($fieldValues->prezime);
        $korisnik->setUsername($fieldValues->username);
        $korisnik->setAdresa($fieldValues->adresa);
        $korisnik->setTelefonFix($fieldValues->fiksniTelefon);
        $korisnik->setTelefonMob($fieldValues->mobilniTelefon);
        $korisnik->setEmail($fieldValues->email);
        $korisnik->setAktivan($fieldValues->aktivan);
        //$korisnik->setAktivan($_POST['aktivanFilter']);
        //$korisnik->setAgencijaID($_POST['ime']);
        //$korisnik->setKlijentID($_POST['ime']);


        $korisnik->setFlagKuca('false');
        $korisnik->setFlagAgencija('false');
        $korisnik->setFlagKlijent('false');

        $korisnik->setAgencijaID('false');
        $korisnik->setKlijentID('false');

        switch ($fieldValues->tipKorisnik) {
            case TipKorisnika::InterniKorisnik:
                //$roleList = $fieldValues->RoleList;
                $korisnik->setRoleList($fieldValues->RoleList);
                $korisnik->setFlagKuca('true');
                break;
            case TipKorisnika::AgencijskiKorisnik:
                $korisnik->setAgencijaID($fieldValues->agencijaID);
                $korisnik->setFlagAgencija('true');
                break;
            case TipKorisnika::KlijentskiKorisnik:
                $korisnik->setKlijentID($fieldValues->klijentID);
                $korisnik->setFlagKlijent('true');
                break;
        }



        //$korisnik->setFunkcijaID($_POST['funkcijaID']);
        if ($fieldValues->password <> '') {
            if ($fieldValues->password <> $fieldValues->passwordPonovo) {
                $response->SetMessage("Password i ponovljeni password moraju biti isti!!!");  
            } else {
                $korisnik->setPassword(md5($fieldValues->password));
                if ($fieldValues->korisnikID == -1) {
                    $action = "KorisnikInsert";
                } else {
                    $action = "KorisnikUpdate";
                }
                $response = $korisnikClass->$action($korisnik);
            }
        } else {
            if ($fieldValues->korisnikID == -1) {



                $action = "KorisnikInsert";
            } else {
                $action = "KorisnikUpdate";
            }
            $response = $korisnikClass->$action($korisnik);
        }
        break;

    case "KorisnikDetailsUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);

        $korisnik->setKorisnikID($fieldValues->korisnikID);
        $korisnik->setIme($fieldValues->ime);
        $korisnik->setPrezime($fieldValues->prezime);
        //$korisnik->setUsername($fieldValues->username);
        //$korisnik->setAdresa($fieldValues->adresa);
        $korisnik->setTelefonFix($fieldValues->fiksniTelefon);
        $korisnik->setTelefonMob($fieldValues->mobilniTelefon);
        $korisnik->setEmail($fieldValues->email);
        //$korisnik->setAktivan($fieldValues->aktivan);
        //$korisnik->setAktivan($_POST['aktivanFilter']);
        //$korisnik->setAgencijaID($_POST['ime']);
        //$korisnik->setKlijentID($_POST['ime']);


        $response = $korisnikClass->KorisnikUpdate($korisnik);

        break;



    case "KorisnikDelete":
        $entryID = $_POST['entryID'];
        $korisnik->setKorisnikID($entryID);
        $response = $korisnikClass->$parameter($korisnik);
        break;
    case "KorisnikGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterKorisnik($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $korisnikClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>