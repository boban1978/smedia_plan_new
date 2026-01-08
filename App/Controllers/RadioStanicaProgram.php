<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$radioStanicaProgramClass = new RadioStanicaProgramClass();
$radioStanicaProgram = new RadioStanicaProgram();

switch ($parameter) {
    case "RadioStanicaProgramLoad":
        $radioStanicaProgramID = $_POST['entryID'];
        $radioStanicaProgram->setRadioStanicaProgramID($radioStanicaProgramID);
        $response = $radioStanicaProgramClass->$parameter($radioStanicaProgram);
        break;
    case "RadioStanicaProgramInsertUpdate":
        $fieldValues = json_decode($_POST['fieldValues']);
        $radioStanicaProgram->setRadioStanicaProgramID($fieldValues->radioStanicaProgramID);
        $radioStanicaProgram->setRadioStanicaID($fieldValues->radioStanicaID);
        $radioStanicaProgram->setNaziv($fieldValues->naziv);
        $radioStanicaProgram->setPocetakEmitovanja($fieldValues->pocetakEmitovanja);
        $radioStanicaProgram->setKrajEmitovanja($fieldValues->krajEmitovanja);
        $radioStanicaProgram->setRadniDan($fieldValues->radniDan);
        $radioStanicaProgram->setAktivan($fieldValues->aktivan);
        if ($fieldValues->radioStanicaProgramID == -1) {
            $action = "RadioStanicaProgramInsert";
        } else {
            $action = "RadioStanicaProgramUpdate";
        }
        $response = $radioStanicaProgramClass->$action($radioStanicaProgram);
        break;
    case "RadioStanicaProgramDelete":
        $radioStanicaProgramID = $_POST['entryID'];
        $radioStanicaProgram->setRadioStanicaProgramID($radioStanicaProgramID);
        $response = $radioStanicaProgramClass->$parameter($radioStanicaProgram);
        break;
    case "RadioStanicaProgramGetForComboBox":
        $radioStationID = $_POST['radioStationID'];
        $response = $radioStanicaProgramClass->$parameter($radioStationID);
        break;
    case "RadioStanicaProgramGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues = json_decode($_POST['filterValues']);
        $filter = new FilterRadioStanicaProgram($filterValues->radioStanicaID, $naziv, $aktivan, $start, $limit, $sort, $dir, $page);
        $response = $radioStanicaProgramClass->$parameter($filter);
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();

?>
