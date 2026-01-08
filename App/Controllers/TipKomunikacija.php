<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$tipKomunikacijaClass = new TipKomunikacijaClass();
$tipKomunikacija = new TipKomunikacija();


switch ($parameter) {
    case "TipKomunikacijaLoad":
        $tipKomunikacijaID = $_POST['entryID'];
        $tipKomunikacija->setTipKomunikacijaID($tipKomunikacijaID);
        $response = $tipKomunikacijaClass->$parameter($tipKomunikacija);
        break;
    case "TipKomunikacijaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $tipKomunikacija->setTipKomunikacijaID($fieldValues->tipKomunikacijaID);
        $tipKomunikacija->setNaziv($fieldValues->naziv);
        $tipKomunikacija->setAktivan($fieldValues->aktivan);
        if ($fieldValues->tipKomunikacijaID == -1) {
            $action = "TipKomunikacijaInsert";
        } else {
            $action = "TipKomunikacijaUpdate";
        }
        $response = $tipKomunikacijaClass->$action($tipKomunikacija);
        break;
    case "TipKomunikacijaDelete":
        $tipKomunikacijaID = $_POST['entryID'];
        $tipKomunikacija->setTipKomunikacijaID($tipKomunikacijaID);
        $response = $tipKomunikacijaClass->$parameter($tipKomunikacija);
        break;
    case "TipKomunikacijaGetForComboBox":
        $response = $tipKomunikacijaClass->$parameter($tipKomunikacija);
        break;
    case "TipKomunikacijaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterTipKomunikacija($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $tipKomunikacijaClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>