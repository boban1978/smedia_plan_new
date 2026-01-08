<?php
require_once __DIR__.'/../../init.php';

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}


$ponudaIstorijaClass = new PonudaIstorijaClass();
$ponudaIstorija = new PonudaIstorija();

switch ($parameter) {
    case "PonudaIstorijaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $ponudaID = $_POST['ponudaID'];
        $filter = new FilterPonudaIstorija($ponudaID, $start, $limit, $sort, $dir, $page);
        $response = $ponudaIstorijaClass->$parameter($filter);
        break;
    case "ShowMediaPlan":
        $dbBroker = new CoreDBBroker();

        $ponudaIstorijaID = $_GET['PonudaIstorijaID'];
        $ponudaIstorijaID+=0;



        if ($korisnik_init['tipKorisnik'] == 3) {

            $query = "SELECT
kampanja.Naziv
FROM
ponudaistorija
INNER JOIN ponuda ON ponudaistorija.PonudaID = ponuda.PonudaID
INNER JOIN kampanja ON kampanja.KampanjaID = ponuda.KampanjaID
WHERE
ponudaistorija.PonudaIstorijaID = ".$ponudaIstorijaID."
AND kampanja.AgencijaID = ".$korisnik_init['agencijaID'];

            $result = $dbBroker->selectOneRow($query);
            if(!$result){
                //echo $query;
                exit;
            }
        }






        $query = "SELECT MediaPlan FROM ponudaistorija WHERE PonudaIstorijaID = " . $ponudaIstorijaID;
        $result = $dbBroker->selectOneRow($query);
        $mediaPlan = $result['MediaPlan'];

        echo $mediaPlan;
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