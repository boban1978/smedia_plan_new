<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$cenovnikClass = new CenovnikClass();
$cenovnik = new Cenovnik();

switch ($parameter) {
    case "CenovnikLoad":
        $cenovnikID = $_POST['entryID'];
        $cenovnik->setCenovnikID($cenovnikID);
        $response = $cenovnikClass->$parameter($cenovnik);
        break;
    case "CenovnikInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $cenovnik->setCenovnikID($fieldValues->cenovnikID);
        $cenovnik->setRadioStanicaID($fieldValues->radioStanicaID);
        $cenovnik->setBlokID($fieldValues->blokID);
        $cenovnik->setKategorijaCenaID($fieldValues->kategorijaCenaID);
        $cenovnik->setCena($fieldValues->cena);
        $cenovnik->setVikend($fieldValues->vikend);
        $cenovnik->setAktivan($fieldValues->aktivan);
        if ($fieldValues->cenovnikID == -1) {
            $action = "CenovnikInsert";
        } else {
            $action = "CenovnikUpdate";
        }
        $response = $cenovnikClass->$action($cenovnik);
        break;
    case "CenovnikDelete":
        $cenovnikID = $_POST['entryID'];
        $cenovnik->setCenovnikID($cenovnikID);
        $response = $cenovnikClass->$parameter($cenovnik);
        break;
    case "CenovnikGetForComboBox":
        $response = $cenovnikClass->$parameter($cenovnik);
        break;
    case "CenovnikGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterCenovnik($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $cenovnikClass->$parameter($filter);
        break;
    case "KategorijaCenaGetForComboBox":
        $kategorijaCena = new KategorijaCena();
        $response = $cenovnikClass->$parameter($kategorijaCena);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();

?>
