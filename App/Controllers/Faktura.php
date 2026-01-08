<?php

require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}


$fakturaClass = new FakturaClass();
$faktura = new Faktura();

switch ($parameter) {


    case "FakturaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $kampanjaID = $_POST['kampanjaID'];
        $filter = new FilterFaktura($kampanjaID, $start, $limit, $sort, $dir, $page);
        $response = $fakturaClass->$parameter($filter);
        break;

    case "ShowDocument":

        $dbBroker = new CoreDBBroker();

        $fakturaID = $_GET['fakturaID'];
        $fakturaID+=0;



        if ($korisnik_init['tipKorisnik'] == 3) {



            $query = "SELECT
kampanja.Naziv
FROM
kampanja
INNER JOIN faktura ON kampanja.KampanjaID = faktura.KampanjaID
WHERE
kampanja.AgencijaID = ".$korisnik_init['agencijaID']."
AND
faktura.FakturaID = " . $fakturaID;

            $result = $dbBroker->selectOneRow($query);
            if(!$result){
                //echo $query;
                exit;
            }
        }



        $query = "SELECT Dokument FROM faktura WHERE fakturaID = " . $fakturaID;
        $result = $dbBroker->selectOneRow($query);
        $dokument = $result['Dokument'];

        echo $dokument;
        exit;

        break;


    default:
        break;
}

ob_clean();
header('Content-type: application/json');
echo $response->GetResponse();
ob_flush();
?>