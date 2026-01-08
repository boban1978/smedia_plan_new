<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$bitanDatumClass = new BitanDatumClass();
$bitanDatum = new BitanDatum();


switch ($parameter) {
    case "BitanDatumLoad":
        $bitanDatumID = $_POST['entryID'];
        $bitanDatum->setBitanDatumID($bitanDatumID);
        $response = $bitanDatumClass->$parameter($bitanDatum);
        break;
    case "BitanDatumInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $bitanDatum->setBitanDatumID($fieldValues->bitanDatumID);
        $bitanDatum->setID($fieldValues->ID);
        $bitanDatum->setVrsta($fieldValues->vrsta);
        $bitanDatum->setDatum($fieldValues->datum);
        $bitanDatum->setOpis($fieldValues->opis);
        $bitanDatum->setAktivan($fieldValues->aktivan);
        if ($fieldValues->bitanDatumID == -1) {
            $action = "BitanDatumInsert";
        } else {
            $action = "BitanDatumUpdate";
        }
        $response = $bitanDatumClass->$action($bitanDatum);
        break;
    case "BitanDatumDelete":
        $bitanDatumID = $_POST['entryID'];
        $bitanDatum->setBitanDatumID($bitanDatumID);
        $response = $bitanDatumClass->$parameter($bitanDatum);
        break;
    case "BitanDatumGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $clientID = $_POST['clientID'];
        //$filterValues= $_POST['filterValues'];
        //$filterValues = json_decode($filterValues);
        $filter = new FilterBitanDatum($clientID, $start, $limit, $sort, $dir, $page);
        $response = $bitanDatumClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>