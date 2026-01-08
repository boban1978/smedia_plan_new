<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];

$kontaktClass = new KontaktClass();
$kontakt = new Kontakt();

switch ($parameter) {
    case "KontaktLoad":       
        $entryID = $_POST['entryID'];
        $kontakt->setKontaktID($entryID);
        $response = $kontaktClass->$parameter($kontakt);
        break;
    case "KontaktInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kontakt->setKontaktID($fieldValues->kontaktID);
        $kontakt->setKlijentID($fieldValues->klijentID);
        $kontakt->setIme($fieldValues->ime);
        $kontakt->setPrezime($fieldValues->prezime);
        $kontakt->setAdresa($fieldValues->adresa);
        $kontakt->setFunkcija($fieldValues->funkcija);
        $kontakt->setTelefon1($fieldValues->telefon1);
        $kontakt->setTelefon2($fieldValues->telefon2);
        $kontakt->setTelefon3($fieldValues->telefon3);
        $kontakt->setEmail($fieldValues->email);
        if ($fieldValues->kontaktID == -1) {
            $action = "KontaktInsert";
        } else {
            $action = "KontaktUpdate";
        }
        $response = $kontaktClass->$action($kontakt);
        break;
    case "KontaktDelete":
        $kontaktID = $_POST['entryID'];
        $kontakt->setKontaktID($kontaktID);
        $response = $kontaktClass->$parameter($kontakt);
        break;
    case "KontaktGetForComboBox":
        $response = $kontaktClass->$parameter($kontakt);
        break;
    case "KontaktGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $klijentID = $_POST['klijentID'];
        $filter = new FilterKontakt($klijentID, $start, $limit, $sort, $dir, $page);
        $response = $kontaktClass->$parameter($filter);
        break;
    default:
        break;
}

ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>