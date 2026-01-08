<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$brendClass = new BrendClass();
$brend = new Brend();


switch ($parameter) {
    case "BrendLoad":
        $entryID = $_POST['entryID'];
        $brend->setBrendID($entryID);
        $response = $brendClass->$parameter($brend);
        break;
    case "BrendInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $brend->setBrendID($fieldValues->brendID);
        $brend->setKlijentID($fieldValues->klijentID);
        $brend->setDelatnostID($fieldValues->delatnostID);
        $brend->setNaziv($fieldValues->naziv);
        if ($fieldValues->brendID == -1) {
            $action = "BrendInsert";
        } else {
            $action = "BrendUpdate";
        }
        $response = $brendClass->$action($brend);
        break;
    case "BrendDelete":
        $entryID = $_POST['entryID'];
        $brend->setBrendID($entryID);
        $response = $brendClass->$parameter($brend);
        break;
    case "BrendGetForComboBox":
        $clientID = $_POST['clientID'];
        $response = $brendClass->$parameter($clientID);
        break;
    case "BrendGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $klijentID= $_POST['klijentID'];
        $response = $brendClass->$parameter($klijentID);       
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>