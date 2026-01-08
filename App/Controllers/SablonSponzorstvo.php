<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$sablonSponzorstvoClass = new SablonSponzorstvoClass();
$sablonSponzorstvo = new SablonSponzorstvo();

switch ($parameter) {
    case "SablonSponzorstvoLoad":
        $radioStanicaProgramID = $_POST['entryID'];
        $sablonSponzorstvo->setRadioStanicaProgramID($radioStanicaProgramID);
        $response = $sablonSponzorstvoClass->$parameter($sablonSponzorstvo);
        break;
    case "SablonSponzorstvoInsertUpdate":
        $fieldValues = json_decode($_POST['fieldValues']);
        $sablonSponzorstvo->setSablonSponzorstvoID($fieldValues->sablonSponzorstvoID);
        $sablonSponzorstvo->setKlijentID($fieldValues->klijentID);
        $sablonSponzorstvo->setRadioStanicaID($fieldValues->radioStanicaID);
        $sablonSponzorstvo->setRadioStanicaProgramID($fieldValues->radioStanicaProgramID);
        $sablonSponzorstvo->setDatumOd($fieldValues->datumOd);
        $sablonSponzorstvo->setDatumDo($fieldValues->datumDo);
        $sablonSponzorstvo->setCenaUkupno($fieldValues->cenaUkupno);
        $sablonSponzorstvo->setCobrending($fieldValues->cobrending);
        $sablonSponzorstvo->setNajavaOdjava($fieldValues->najavaOdjava);
        $sablonSponzorstvo->setNajavaEmisije($fieldValues->najavaEmisije);
        $sablonSponzorstvo->setPrsegment($fieldValues->prsegment);
        $sablonSponzorstvo->setPremiumBlok($fieldValues->premiumBlok);
        $sablonSponzorstvo->setKorisnikID($_SESSION['sess_idkor']);
        $action = "SablonSponzorstvoInsert";
//        if ($fieldValues->sablonSponzorstvoID == -1) {
//            $action = "SablonSponzorstvoInsert";
//        } else {
//            $action = "SablonSponzorstvoUpdate";
//        }
        $response = $sablonSponzorstvoClass->$action($sablonSponzorstvo);
        break;
    case "SablonSponzorstvoDelete":
        $sablonSponzorstvoID = $_POST['entryID'];
        $sablonSponzorstvo->setSablonSponzorstvoID($sablonSponzorstvoID);
        $response = $sablonSponzorstvoClass->$parameter($sablonSponzorstvo);
        break;
    case "SablonSponzorstvoGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues = json_decode($_POST['filterValues']);
        $filter = new FilterSablonSponzorstvo($filterValues->radioStanicaID, $start, $limit, $sort, $dir, $page);
        $response = $sablonSponzorstvoClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>
