<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];

$ponudaClass = new PonudaClass();
$ponuda = new Ponuda();

switch ($parameter) {
    case "PonudaLoad":       
        $entryID = $_POST['entryID'];
        $ponuda->setPonudaID($entryID);
        $response = $ponudaClass->$parameter($ponuda);
        break;
    case "PonudaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $ponuda->setPonudaID($fieldValues->ponudaID);
        $ponuda->setKlijentID($fieldValues->klijentID);
        $ponuda->setKorisnikID($_SESSION['sess_idkor']);
        $ponuda->setSadrzaj($fieldValues->sadrzaj);
        $ponuda->setVrednost($fieldValues->vrednost);
        $ponuda->setStatusPonudaID($fieldValues->statusPonudaID);
        $ponuda->setVremePostavke($fieldValues->vremePostavke);
        if ($fieldValues->ponudaID == -1) {
            $action = "PonudaInsert";
        } else {
            $action = "PonudaUpdate";
        }
        $response = $ponudaClass->$action($ponuda);
        break;
    case "PonudaDelete":
        $ponudaID = $_POST['entryID'];
        $ponuda->setPonudaID($ponudaID);
        $response = $ponudaClass->$parameter($ponuda);
        break;
    case "PonudaGetForComboBox":
        $response = $ponudaClass->$parameter($ponuda);
        break;
    case "PonudaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $klijentID = $_POST['klijentID'];
        $filter = new FilterPonuda($klijentID, $start, $limit, $sort, $dir, $page);
        $response = $ponudaClass->$parameter($filter);
        break;
    default:
        break;
}

ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>