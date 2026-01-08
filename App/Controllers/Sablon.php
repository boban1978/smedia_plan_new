<?php
require_once __DIR__.'/../../init.php';

if(isset($_GET['action'])) {
    $paraGet = $_GET['action'];
}
$parameter = $_POST['action'];
if (isset($paraGet)) {
    $parameter = $paraGet;
}


//$parameter="SablonGetList";


$sablonClass = new SablonClass();
$sablon = new Sablon();

switch ($parameter) {
    case "SablonLoad":       
        $entryID = $_POST['sablonID'];
        $sablon->setSablonID($entryID);
        $response = $sablonClass->$parameter($sablon);
        break;
    case "SablonPotvrdi":
        $tmpSablon = unserialize($_SESSION['tmpKampanja']);
        $response = $sablonClass->$parameter($tmpSablon);
        break;
    case "SablonInsertUpdate":
        $sablon->setSablonID($_POST['sablonID']);
        $sablon->setRadioStanicaID($_POST['radioStanicaID']);
        $sablon->setNaziv($_POST['naziv']);
        $sablon->setTrajanje($_POST['trajanje']);
        $sablon->setPopust($_POST['popust']);
        $sablon->setKorisnikID($_SESSION['sess_idkor']);
        $sablon->setAktivan($_POST['aktivan']);



        $daniStampa="";
        $ucestalost_arr=array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);

        foreach ($_POST as $key => $value) {



            if (strpos($key, "dan") !== false && strlen($key) == 4) {
                $daniStampa .= $value;
                $daniStampa .= ",";
            }


            if (strpos($key, "Ucestalost1") !== false && !empty($value)) {
                $ucestalost_arr[1] = $value;
            }
            if (strpos($key, "Ucestalost2") !== false && !empty($value)) {
                $ucestalost_arr[2] = $value;
            }
            if (strpos($key, "Ucestalost3") !== false && !empty($value)) {
                $ucestalost_arr[3] = $value;
            }
            if (strpos($key,"Ucestalost4") !== false && !empty($value)) {
                $ucestalost_arr[4] = $value;
            }
            if (strpos($key, "Ucestalost5") !== false && !empty($value)) {
                $ucestalost_arr[5] = $value;
            }
        }
        $daniStampa = substr($daniStampa, 0, -1);
        $sablon->setDaniZaEmitovanje($daniStampa);


        $ucestalost="";
        foreach($ucestalost_arr as $key => $value){
            $ucestalost.=$value.",";
        }
        $ucestalost = substr($ucestalost, 0, -1);
        $sablon->setUcestalost($ucestalost);



        //if ($fieldValues->sablonID == -1) {
            $action = "SablonInsert";
//        } else {
//            $action = "SablonUpdate";
//        }
        $response = $sablonClass->$action($sablon);
        break;

    case "SablonDelete":
        $sablonID = $_POST['entryID'];
        $sablon->setSablonID($sablonID);
        $response = $sablonClass->$parameter($sablon);
        break;
    case "SablonGetForComboBox":
        $radioStanicaID = $_POST['RadioStanicaID'];
        $sablon->setRadioStanicaID($radioStanicaID);
        $response = $sablonClass->$parameter($sablon);
        break;

    case "SablonGetList":

        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $filterValues= $_POST['filterValues'];
        $filterValues = json_decode($filterValues);
        $filter = new FilterSablon($filterValues, $start, $limit, $sort, $dir, $page);

        $response = $sablonClass->$parameter($filter);
        break;


    case "SablonLoadDetails":
        $entryID = $_POST['sablonID'];
        $sablon->setSablonID($entryID);
        $response = $sablonClass->$parameter($sablon);
        break;





    default:
        break;
}

ob_clean();
//header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>