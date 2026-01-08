<?php
require_once __DIR__.'/../../init.php';

//include_once '../Class/FileInfoClass.php';
//include_once '../Class/UploadedFileClass.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}

$ponudaNapomenaClass = new PonudaDokumentClass();
$ponudaNapomena = new PonudaDokument();

switch ($parameter) {
    case "PonudaDokumentLoad":       
        $entryID = $_POST['entryID'];
        $ponudaNapomena->setPonudaDokumentID($entryID);
        $response = $ponudaNapomenaClass->$parameter($ponudaNapomena);
        break;
    case "PonudaDokumentInsertUpdate":
        //$fieldValues = $_POST['fieldValues'];
        $prilog = $_FILES['prilog'];
        $file = new UploadedFileClass($prilog);
        $fileInfo = new FileInfoClass($file, DokumentLokacije::PonudaDokument);
        if ($fileInfo->GetResponse()){ 
            //$fieldValues = json_decode($fieldValues);
            $ponudaNapomena->setPonudaDokumentID($_POST['ponudaDokumentID']);
            $ponudaNapomena->setPonudaID($_POST['ponudaID']);
            $ponudaNapomena->setNaziv($fileInfo->Name);
            $ponudaNapomena->setLink($fileInfo->Link);
            $ponudaNapomena->setKorisnikID($_SESSION['sess_idkor']);
            //$ponudaDokument->setVremePostavke($fieldValues->vremePostavke);
            if ($_POST['ponudaDokumentID'] == -1) {
                $action = "PonudaDokumentInsert";
            } else {
                $action = "PonudaDokumentUpdate";
            }
            $response = $ponudaNapomenaClass->$action($ponudaNapomena);
        } else {
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess('false');
            $response->SetMessage("Neuspešno kopiranje fajla!");
        }
        break;
    case "PonudaDokumentDelete":
        $ponudaDokumentID = $_POST['entryID'];
        $ponudaNapomena->setPonudaDokumentID($ponudaDokumentID);
        $response = $ponudaNapomenaClass->$parameter($ponudaNapomena);
        break;
    case "PonudaDokumentGetForComboBox":
        $response = $ponudaNapomenaClass->$parameter($ponudaNapomena);
        break;
    case "PonudaDokumentGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $ponudaID = $_POST['ponudaID'];
        $filter = new FilterPonudaDokument($ponudaID, $start, $limit, $sort, $dir, $page);
        $response = $ponudaNapomenaClass->$parameter($filter);
        break;
    default:
        break;
}

ob_clean();
//header('Content-type: application/json');
header('Content-Type: text/html');
echo $response->GetResponse();
ob_flush();
?>