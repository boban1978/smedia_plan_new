<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$statusPonudaClass = new StatusPonudaClass();
$statusPonuda = new StatusPonuda();


switch ($parameter) {
    case "StatusPonudaLoad":
        $entryID = $_POST['entryID'];
        $statusPonuda->setStatusPonudaID($entryID);
        $response = $statusPonudaClass->$parameter($statusPonuda);
        break;
    case "StatusPonudaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $statusPonuda->setStatusPonudaID($fieldValues->statusPonudaID);
        $statusPonuda->setNaziv($fieldValues->naziv);
        $statusPonuda->setAktivan($fieldValues->aktivan);
        if ($fieldValues->statusPonudaID == -1) {
            $action = "StatusPonudaInsert";
        } else {
            $action = "StatusPonudaUpdate";
        }
        $response = $statusPonudaClass->$action($statusPonuda);
        break;
    case "StatusPonudaDelete":
        $entryID = $_POST['entryID'];
        $statusPonuda->setStatusPonudaID($entryID);
        $response = $statusPonudaClass->$parameter($statusPonuda);
        break;
    case "StatusPonudaGetForComboBox":
        $response = $statusPonudaClass->$parameter($statusPonuda);
        break;
    case "StatusPonudaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterStatusPonuda($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $statusPonudaClass->$parameter($filter);       
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
