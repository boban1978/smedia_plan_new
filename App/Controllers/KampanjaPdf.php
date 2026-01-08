<?php

ob_start();
require_once __DIR__.'/../../init.php';

require_once('../../html2pdf_v4.03/html2pdf.class.php');

$kampanjaID = 0+$_GET['kampanjaID'];



/*
if($kampanjaID==2000){

    $dbBroker = new CoreDBBroker();


    $query = "SELECT *
FROM
kampanjablok
WHERE
KampanjaID = 959";

    $result = $dbBroker->selectManyRows($query);
    foreach ($result['rows'] as $row) {
        echo $row['RadioStanicaID']." xxx ".$row['BlokID']." xxx ".$row['Datum']." xxx ".$row['Redosled']."<br/>";





        $testDay = date("N", strtotime($row['Datum']));
        if (in_array($testDay, array(6, 7))) {
            $vikend = 1;
        } else {
            $vikend = 0;
        }





        $query1 = "select C.Cena
                                    from cenovnik C
                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID
                                    where 12 >= KC.TrajanjeOd and  12 <= KC.TrajanjeDo AND C.BlokID = ".$row['BlokID']." AND C.RadioStanicaID = ".$row['RadioStanicaID']."
                                    and C.Vikend = $vikend";


        $dbBroker = new CoreDBBroker();
        $result1 = $dbBroker->selectOneRow($query1);
        $price = number_format($result1['Cena'] - ($result1['Cena'] * $discount / 100), 2);


        if($row['Redosled']==1){
            $price=(float)$price*1.2;
        }
        if($row['Redosled']==2){

        }
        if($row['Redosled']>2){
            $price=(float)$price*0.8;
        }




        $query = "Update
blokzauzece SET ZauzetoSekundi = ZauzetoSekundi + 11 , NepotvrdjenoSekundi = NepotvrdjenoSekundi + 11 , PreostaloSekundi = PreostaloSekundi-11
WHERE
RadioStanicaID = ".$row['RadioStanicaID']." AND BlokID =".$row['BlokID']." AND Datum ='".$row['Datum']."'";
        $result5 = $dbBroker->simpleQuery($query);





        $query = "Update
kampanjablok SET CenaEmitovanja = $price
WHERE
RadioStanicaID = ".$row['RadioStanicaID']." AND BlokID =".$row['BlokID']." AND Datum ='".$row['Datum']."'  AND KampanjaID =959";

        $result5 = $dbBroker->simpleQuery($query);






    }


    exit;
}

*/




$kampanjaPdfClass = new KampanjaPdfClass();
$html = $kampanjaPdfClass->MediaPlanForCampaigne($kampanjaID);




if ($korisnik_init['tipKorisnik'] == 3) {

    $dbBroker = new CoreDBBroker();

    $query = "SELECT
kampanja.Naziv
FROM
kampanja
WHERE
kampanja.AgencijaID = ".$korisnik_init['agencijaID']."
AND kampanja.KampanjaID = ".$kampanjaID;

    $result = $dbBroker->selectOneRow($query);
    if(!$result){
        //echo $query;
        exit;
    }
}












try {
    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(15, 5, 15, 5));
    $html2pdf->pdf->SetDisplayMode('fullpage');
    //$html2pdf->pdf->SetAutoPageBreak(TRUE, 30);
    ob_get_clean();
    //header('Content-Type: appliacation/JSON');
    $html2pdf->writeHTML($html);
    $html2pdf->Output('exemple02.pdf', 'D');
    header('Content-Type: application/pdf');
    echo "{success: true}";
} catch (HTML2PDF_exception $e) {
    //echo $e->getMessage();
    echo "{success: false}";
    exit;
}
?>
