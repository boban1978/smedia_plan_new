<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}

$ponudaNapomenaClass = new PonudaNapomenaClass();
$ponudaNapomena = new PonudaNapomena();

switch ($parameter) {
    case "PonudaNapomenaLoad":       
        $entryID = $_POST['entryID'];
        $ponudaNapomena->setPonudaNapomenaID($entryID);
        $response = $ponudaNapomenaClass->$parameter($ponudaNapomena);
        break;
    case "PonudaNapomenaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $ponudaNapomena->setPonudaNapomenaID($fieldValues->ponudaNapomenaID);
        $ponudaNapomena->setPonudaID($fieldValues->ponudaID);
        $ponudaNapomena->setStatusPonudaID($fieldValues->statusPonudaID);
        $ponudaNapomena->setNapomena($fieldValues->napomena);
        $ponudaNapomena->setKorisnikID($_SESSION['sess_idkor']);
        if ($fieldValues->ponudaNapomenaID == -1) {
            $action = "PonudaNapomenaInsert";
        } else {
            $action = "PonudaNapomenaUpdate";
        }
        $response = $ponudaNapomenaClass->$action($ponudaNapomena);
        break;
    case "PonudaNapomenaDelete":
        $entryID = $_POST['entryID'];
        $ponudaNapomena->setPonudaNapomenaID($entryID);
        $response = $ponudaNapomenaClass->$parameter($ponudaNapomena);
        break;
    case "PonudaNapomeneGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $ponudaID = $_POST['ponudaID'];
        $filter = new FilterPonudaNapomena($ponudaID, $start, $limit, $sort, $dir, $page);
        $response = $ponudaNapomenaClass->$parameter($filter);
        break;
    default:
        break;
}

ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>