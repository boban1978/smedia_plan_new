<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$tipPlacanjaClass = new TipPlacanjaClass();
$tipPlacanja = new TipPlacanja();


switch ($parameter) {
    case "TipPlacanjaLoad":
        $entryID = $_POST['entryID'];
        $tipPlacanja->setTipPlacanjaID($entryID);
        $response = $tipPlacanjaClass->$parameter($tipPlacanja);
        break;
    /*
    case "TipPlacanjaInsertUpdate":
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
    case "TipPlacanjaDelete":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setTipUgovoraID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    */
    case "TipPlacanjaGetForComboBox":
        $response = $tipPlacanjaClass->$parameter($tipPlacanja);
        break;
    /*
    case "TipPlacanjaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterTipPlacanja($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $tipPlacanjaClass->$parameter($filter);*/
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
