<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$delatnostClass = new DelatnostClass();
$delatnost = new Delatnost();


switch ($parameter) {
    case "DelatnostLoad":
        $entryID = $_POST['entryID'];
        $delatnost->setDelatnostID($entryID);
        $response = $delatnostClass->$parameter($delatnost);
        break;
    case "DelatnostInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $delatnost->setDelatnostID($fieldValues->delatnostID);
        $delatnost->setNaziv($fieldValues->naziv);
        $delatnost->setAktivan($fieldValues->aktivan);
        if ($fieldValues->delatnostID == -1) {
            $action = "DelatnostInsert";
        } else {
            $action = "DelatnostUpdate";
        }
        $response = $delatnostClass->$action($delatnost);
        break;
    case "DelatnostDelete":
        $entryID = $_POST['entryID'];
        $delatnost->setDelatnostID($entryID);
        $response = $delatnostClass->$parameter($delatnost);
        break;
    case "DelatnostGetForComboBox":
        $clientID = $_POST['clientID'];
        $response = $delatnostClass->$parameter($delatnost, $clientID);
        break;
    case "DelatnostGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterDelatnost($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $delatnostClass->$parameter($filter);       
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>