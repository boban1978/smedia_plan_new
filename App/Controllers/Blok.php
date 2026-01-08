<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$blokClass = new BlokClass();
$blok = new Blok();


switch ($parameter) {
    case "BlokLoad":
        $blokID = $_POST['entryID'];
        $blok->setBlokID($blokID);
        $response = $blokClass->$parameter($blok);
        break;
    case "BlokInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $blok->setBlokID($fieldValues->blokID);
        $blok->setSat($fieldValues->sat);
        $blok->setRedniBrojSat($fieldValues->redniBrojSat);
        $blok->setVrsta($fieldValues->vrsta);
        $blok->setVremeStart(date("H:i:s",mktime($fieldValues->sat,$fieldValues->vremeStartMin,$fieldValues->vremeStartSec)));
        $blok->setVremeEnd(date("H:i:s",mktime($fieldValues->sat,$fieldValues->vremeEndMin,$fieldValues->vremeEndSec)));
        $blok->setTrajanje($fieldValues->trajanje);
        $blok->setAktivan($fieldValues->aktivan);        
        if ($fieldValues->blokID == -1) {
            $action = "BlokInsert";
        } else {
            $action = "BlokUpdate";
        }
        $response = $blokClass->$action($blok);
        break;
    case "BlokDelete":
        $blokID = $_POST['entryID'];
        $blok->setBlokID($blokID);
        $response = $blokClass->$parameter($blok);
        break;
    case "BlokGetForComboBox":
        $response = $blokClass->$parameter($blok);
        break;
    case "BlokGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterBlok($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $blokClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();

?>
