<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$kampanjaNacinPlacanjaClass = new TipUgovoraClass();
$kampanjaNacinPlacanja = new TipUgovora();


switch ($parameter) {
    case "TipUgovoraLoad":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setTipUgovoraID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "TipUgovoraInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanjaNacinPlacanja->setTipUgovoraID($fieldValues->tipUgovoraID);
        $kampanjaNacinPlacanja->setNaziv($fieldValues->naziv);
        $kampanjaNacinPlacanja->setAktivan($fieldValues->aktivan);
        if ($fieldValues->tipUgovoraID == -1) {
            $action = "TipUgovoraInsert";
        } else {
            $action = "TipUgovoraUpdate";
        }
        $response = $kampanjaNacinPlacanjaClass->$action($kampanjaNacinPlacanja);
        break;
    case "TipUgovoraDelete":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setTipUgovoraID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "TipUgovoraGetForComboBox":
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "TipUgovoraGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterTipUgovora($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $kampanjaNacinPlacanjaClass->$parameter($filter);
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
