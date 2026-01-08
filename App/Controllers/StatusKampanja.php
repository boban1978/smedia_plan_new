<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$kampanjaNacinPlacanjaClass = new StatusKampanjaClass();
$kampanjaNacinPlacanja = new StatusKampanja();


switch ($parameter) {
    case "StatusKampanjaLoad":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setStatusKampanjaID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "StatusKampanjaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanjaNacinPlacanja->setStatusKampanjaID($fieldValues->statusKampanjaID);
        $kampanjaNacinPlacanja->setNaziv($fieldValues->naziv);
        $kampanjaNacinPlacanja->setAktivan($fieldValues->aktivan);
        if ($fieldValues->statusKampanjaID == -1) {
            $action = "StatusKampanjaInsert";
        } else {
            $action = "StatusKampanjaUpdate";
        }
        $response = $kampanjaNacinPlacanjaClass->$action($kampanjaNacinPlacanja);
        break;
    case "StatusKampanjaDelete":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setStatusKampanjaID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "StatusKampanjaForComboBox":
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "StatusKampanjaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterStatusKampanja($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $kampanjaNacinPlacanjaClass->$parameter($filter);
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
