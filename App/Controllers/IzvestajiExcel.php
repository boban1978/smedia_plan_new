<?php
ob_start();
require_once __DIR__.'/../../init.php';

//require_once('../../html2pdf_v4.03/html2pdf.class.php');




require_once "../../writeexcel/class.writeexcel_workbook.inc.php";
require_once "../../writeexcel/class.writeexcel_worksheet.inc.php";

/*
require_once "../../writeexcel/class.writeexcel_biffwriter.inc.php";
require_once "../../writeexcel/class.writeexcel_format.inc.php";
require_once "../../writeexcel/class.writeexcel_formula.inc.php";
require_once "../../writeexcel/class.writeexcel_olewriter.inc.php";*/
//require_once "../../writeexcel/class.writeexcel_workbookbig.inc.php";




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
        $html = $izvestajClass->ReportFinancial($fieldValues,'excel');
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



/*
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en', false, 'UTF-8', array(15, 5, 15, 5));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        //$html2pdf->pdf->SetAutoPageBreak(TRUE, 30);
        
        ob_get_clean();
        //header('Content-Type: appliacation/JSON');
        $html2pdf->writeHTML($html);
        $html2pdf->Output('exemple02.pdf', 'D');
        header('Content-Type: application/pdf');
        echo "{success: true}";
        
    }
    catch(HTML2PDF_exception $e) {
        echo "{success: false}";   
        exit;
    } */
?>
