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

$fieldValues = $_GET['fieldValues'];
$fieldValues = json_decode($fieldValues);

if($korisnik_init['tipKorisnik']==3){
    $fieldValues->agencijaID=$korisnik_init['agencijaID'];
    $fieldValues->komercijalistaID=$korisnik_init['korisnikID'];
}


$fieldValues->download_token=0+$_GET['download_token'];


switch ($parameter) {
    case "report-financial":
        $html = $izvestajClass->ReportFinancial($fieldValues,'pdf');
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
    try
    {
        $html2pdf = new HTML2PDF('L', 'A4', 'en', false, 'UTF-8', array(15, 5, 15, 5));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        //$html2pdf->pdf->SetAutoPageBreak(TRUE, 30);
        
        ob_get_clean();
        //header('Content-Type: appliacation/JSON');

        $html2pdf->writeHTML($html);


        if ($fieldValues->download_token > 0) {
            setcookie("download_token", $fieldValues->download_token, time() + (86400 * 30), "/");
            //echo $_COOKIE['rand']."hghh";
        }else{
            unset($_COOKIE['download_token']);
            setcookie('download_token', null, -1, '/');
            //echo "nooooo";
        }



        header('Content-Type: application/pdf');
        $html2pdf->Output('Izvestaj.pdf', 'D');

        //echo "{success: true}";


        
    }
    catch(HTML2PDF_exception $e) {
        //echo "{success: false}";
        //exit;
    } 
?>
