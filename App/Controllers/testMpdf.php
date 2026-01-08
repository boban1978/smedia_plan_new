<?php
require_once __DIR__.'/../../init.php';

//require_once('../../mpdf/mpdf.php');
require_once('../../html2pdf_v4.03/html2pdf.class.php');
//ini_set('memory_limit','128M');
$izvestajClass = new IzvestajClass();
//$testHtml = $izvestajClass->ReportCommunication($filter);
//$testHtml = $izvestajClass->ReportOffers($filter);
$testHtml = $izvestajClass->ReportFinancial($filter);

//$mpdf = new mPDF();
//$html = ob_get_clean();
//$mpdf->WriteHTML($testHtml);
//$mpdf->Output();

try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(15, 5, 15, 5));
        $html2pdf->pdf->SetDisplayMode('fullpage');
        ob_get_clean();
        //header('Content-Type: application/pdf');
        $html2pdf->writeHTML($testHtml);
        $html2pdf->Output('exemple02.pdf', 'D');      
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
?>