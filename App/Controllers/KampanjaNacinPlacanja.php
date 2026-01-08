<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$kampanjaNacinPlacanjaClass = new KampanjaNacinPlacanjaClass();
$kampanjaNacinPlacanja = new KampanjaNacinPlacanja();


switch ($parameter) {
    case "KampanjaNacinPlacanjaLoad":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setKampanjaNacinPlacanjaID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "KampanjaNacinPlacanjaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanjaNacinPlacanja->setKampanjaNacinPlacanjaID($fieldValues->kampanjaNacinPlacanjaID);
        $kampanjaNacinPlacanja->setNaziv($fieldValues->naziv);
        $kampanjaNacinPlacanja->setAktivan($fieldValues->aktivan);
        if ($fieldValues->kampanjaNacinPlacanjaID == -1) {
            $action = "KampanjaNacinPlacanjaInsert";
        } else {
            $action = "KampanjaNacinPlacanjaUpdate";
        }
        $response = $kampanjaNacinPlacanjaClass->$action($kampanjaNacinPlacanja);
        break;
    case "KampanjaNacinPlacanjaDelete":
        $entryID = $_POST['entryID'];
        $kampanjaNacinPlacanja->setKampanjaNacinPlacanjaID($entryID);
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "KampanjaNacinPlacanjaForComboBox":
        $response = $kampanjaNacinPlacanjaClass->$parameter($kampanjaNacinPlacanja);
        break;
    case "KampanjaNacinPlacanjaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterKampanjaNacinPlacanja($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $kampanjaNacinPlacanjaClass->$parameter($filter);
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
