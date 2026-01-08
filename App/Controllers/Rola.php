<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];


$rolaClass = new RolaClass();
$rola = new Rola();


switch ($parameter) {
    case "RolaLoad":
        $entryID = $_POST['entryID'];
        $rola->setRolaID($entryID);
        $response = $rolaClass->$parameter($rola);
        break;
    case "RolaInsertUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $rola->setRolaID($fieldValues->rolaID);
        $rola->setNaziv($fieldValues->naziv);
        $rola->setOpis($fieldValues->opis);
        $rola->setAktivan($fieldValues->aktivan);
        $rola->setPermisijaList($fieldValues->permisijaList);
        if ($fieldValues->rolaID == -1) {
            $action = "RolaInsert";
        } else {
            $action = "RolaUpdate";
        }
        $response = $rolaClass->$action($rola);
        break;
    case "RolaDelete":
        $entryID = $_POST['entryID'];
        $rola->setRolaID($entryID);
        $response = $rolaClass->$parameter($rola);
        break;
    case "RolaGetForComboBox":
        $response = $rolaClass->$parameter($rola);
        break;
    case "PermisijaGetForComboBox":
        $permisijaClass = new PermisijaClass();
        $permisija = new Permisija();
        $response = $permisijaClass->$parameter($permisija);
        break;  
    case "PermisijaGetForUser":
        $permisijaClass = new PermisijaClass();
        $permisija = new Permisija();
        $response = $permisijaClass->$parameter($_SESSION['sess_idkor']);
        break; 
    case "RolaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterRola($filterValues, $start, $limit, $sort, $dir, $page);
        $response = $rolaClass->$parameter($filter);   
        break;
    default:
        break;
}
ob_clean();
echo $response->GetResponse();
ob_flush();
?>