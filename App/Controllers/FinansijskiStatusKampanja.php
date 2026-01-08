<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$finansijskiStatusClass = new FinansijskiStatusKampanjaClass();
$finansijskiStatus = new FinansijskiStatusKampanja();


switch ($parameter) {
    case "FinansijskiStatusLoad":
        $entryID = $_POST['entryID'];
        $finansijskiStatus->setFinansijskiStatusKampanjaID($entryID);
        $response = $finansijskiStatusClass->$parameter($finansijskiStatus);
        break;
    case "FinansijskiStatusInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $finansijskiStatus->setFinansijskiStatusKampanjaID($fieldValues->finansijskiStatusKampanjaID);
        $finansijskiStatus->setNaziv($fieldValues->naziv);
        $finansijskiStatus->setAktivan($fieldValues->aktivan);
        if ($fieldValues->finansijskiStatusKampanjaID == -1) {
            $action = "FinansijskiStatusInsert";
        } else {
            $action = "FinansijskiStatusUpdate";
        }
        $response = $finansijskiStatusClass->$action($finansijskiStatus);
        break;
    case "FinansijskiStatusDelete":
        $entryID = $_POST['entryID'];
        $finansijskiStatus->setFinansijskiStatusKampanjaID($entryID);
        $response = $finansijskiStatusClass->$parameter($finansijskiStatus);
        break;
    case "FinansijskiStatusGetForComboBox":
        $response = $finansijskiStatusClass->$parameter($finansijskiStatus);
        break;
    case "FinansijskiStatusGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterFinansijskiStatusKampanja($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $finansijskiStatusClass->$parameter($filter);       
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>