<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}

$istorijaKomunikacijaClass = new IstorijaKomunikacijaClass();
$istorijaKomunikacija = new IstorijaKomunikacija();

switch ($parameter) {
    case "IstorijaKomunikacijaLoad":       
        $entryID = $_POST['entryID'];
        $istorijaKomunikacija->setIstorijaKomunikacijaID($entryID);
        $response = $istorijaKomunikacijaClass->$parameter($istorijaKomunikacija);
        break;
    case "IstorijaKomunikacijaInsertUpdate":
        //$fieldValues = $_POST['fieldValues'];
        $prilog = $_FILES['prilog'];
        //$fieldValues = json_decode($fieldValues);
        //Upload Priloga
        if ($prilog['size'] > 0) {
            $file = new UploadedFileClass($prilog);

            $fileInfo = new FileInfoClass($file, DokumentLokacije::IstorijaKomunikacijeDokument);
            $istorijaKomunikacija->setPrilogNaziv($fileInfo->Name);
            $istorijaKomunikacija->setPrilogLink($fileInfo->Link);
            if ($fileInfo->GetResponse()){ 
                //$istorijaKomunikacija->setIstorijaKomunikacijaID($_POST['istorijaKomunikacijaID']);
                $istorijaKomunikacija->setKlijentID($_POST['klijentID']);
                $istorijaKomunikacija->setKorisnikID($_SESSION['sess_idkor']);
                $istorijaKomunikacija->setDatumKomunikacije($_POST['datumKomunikacije']);
                $istorijaKomunikacija->setZaveoID($_SESSION['sess_idkor']);
                $istorijaKomunikacija->setTipKomunikacijaID($_POST['tipKomunikacijaID']);
                $istorijaKomunikacija->setNapomena($_POST['napomena']);
                $action = "IstorijaKomunikacijaInsert";
                $response = $istorijaKomunikacijaClass->$action($istorijaKomunikacija);
            } else {
                $response = new CoreAjaxResponseInfo();
                $response->SetSuccess('false');
                $response->SetMessage("Neuspešno kopiranje fajla!");
            }
            
        } else { 
            //$istorijaKomunikacija->setIstorijaKomunikacijaID($_POST['istorijaKomunikacijaID']);
            $istorijaKomunikacija->setKlijentID($_POST['klijentID']);
            $istorijaKomunikacija->setKorisnikID($_SESSION['sess_idkor']);
            $istorijaKomunikacija->setDatumKomunikacije($_POST['datumKomunikacije']);
            $istorijaKomunikacija->setZaveoID($_SESSION['sess_idkor']);
            $istorijaKomunikacija->setTipKomunikacijaID($_POST['tipKomunikacijaID']);
            $istorijaKomunikacija->setNapomena($_POST['napomena']);
            $action = "IstorijaKomunikacijaInsert";
            $response = $istorijaKomunikacijaClass->$action($istorijaKomunikacija);
        } 
        break;
    case "IstorijaKomunikacijaDelete":
        $istorijaKomunikacijaID = $_POST['entryID'];
        $istorijaKomunikacija->setIstorijaKomunikacijaID($istorijaKomunikacijaID);
        $response = $istorijaKomunikacijaClass->$parameter($istorijaKomunikacija);
        break;
//    case "IstorijaKomunikacijaGetForComboBox":
//        $response = $istorijaKomunikacijaClass->$parameter($istorijaKomunikacija);
//        break;
    case "IstorijaKomunikacijaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $klijentID = $_POST['klijentID'];
        //$filterValues = json_decode($filterValues);
        $filter = new FilterIstorijaKomunikacija($klijentID, $start, $limit, $sort, $dir, $page);
        $response = $istorijaKomunikacijaClass->$parameter($filter);
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