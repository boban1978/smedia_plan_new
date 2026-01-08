<?php
ob_start();
require_once __DIR__.'/../../init.php';

require_once('../../html2pdf_v4.03/html2pdf.class.php');

$paraGet = $_GET['action'];
$parameter = $_POST['action'];
if ($paraGet) {
    $parameter = $paraGet;
}
$izvestajClass = new IzvestajClass();

$fieldValues = $_POST['fieldValues'];
$fieldValues = json_decode($fieldValues);


if($korisnik_init['tipKorisnik']==3){
    $fieldValues->agencijaID=$korisnik_init['agencijaID'];
    $fieldValues->komercijalistaID=$korisnik_init['korisnikID'];
}

//send_respons_boban($fieldValues);


switch ($parameter) {
    case "report-financial":
        $html = $izvestajClass->ReportFinancial($fieldValues);
        break;
    case "report-communication":
        $html = $izvestajClass->ReportCommunication($fieldValues);
        break;
    case "report-offers":
        $html = $izvestajClass->ReportOffers($fieldValues);
        break;

    default:
        break;
}
ob_get_clean();
header('Content-type: application/json');
$item['html'] = $html;
echo '{success: true, data:['.json_encode($item).']}';
?>
