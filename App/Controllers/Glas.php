<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$glasClass = new GlasClass();
$glas = new Glas();


switch ($parameter) {
    case "GlasLoad":
        $radioStanicaID = $_POST['entryID'];
        $glas->setGlasID($radioStanicaID);
        $response = $glasClass->$parameter($glas);
        break;
    case "GlasInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $glas->setGlasID($fieldValues->glasID);
        $glas->setImePrezime($fieldValues->imePrezime);
        $glas->setAktivan($fieldValues->aktivan);
        if ($fieldValues->glasID == -1) {
            $action = "GlasInsert";
        } else {
            $action = "GlasUpdate";
        }
        $response = $glasClass->$action($glas);
        break;
    case "GlasDelete":
        $glasID = $_POST['entryID'];
        $glas->setGlasID($glasID);
        $response = $glasClass->$parameter($glas);
        break;
    case "GlasGetForComboBox":
        $response = $glasClass->$parameter($glas);
        break;
    case "GlasGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterGlas($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $glasClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>