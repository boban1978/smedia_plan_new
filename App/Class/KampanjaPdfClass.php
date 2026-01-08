<?php
/**
 * Description of IzvestajClass
 *
 * @author n.lekic
 */
class KampanjaPdfClass {
    
    public function MediaPlanForCampaigne($campaigneID) {




        $query = "SELECT
            radiostanica.Naziv,
            radiostanica.Adresa,
            radiostanica.Logo
            FROM
            kampanja
            INNER JOIN radiostanica ON kampanja.RadioStanicaID = radiostanica.RadioStanicaID
            WHERE
            kampanja.KampanjaID = ". $campaigneID;

        $dbBroker = new CoreDBBroker();
        $result_radio_stanica = $dbBroker->selectOneRow($query);




         $query = "select kb.Datum, 
                    k.Naziv as Kampanja,
                    k.CenaUkupno as CenaUkupno,
                    k.Popust as Popust,
                    s.SpotName as Spot, 
                    concat(s.SpotTrajanje, ' sek.') as Trajanje, 
                    concat(lpad(b.Sat, 2, '0'), ' h') as SatOd,
                    concat(lpad(if(b.Sat+1 = 24, 0, b.Sat+1), 2, '0'), ' h') as SatDo
                    from kampanjablok as kb
                    inner join kampanja k on kb.KampanjaID = k.KampanjaID
                    inner join spot s on kb.SpotID = s.SpotID
                    inner join blok b on kb.BlokID = b.BlokID
                    where kb.KampanjaID = $campaigneID
                    order by kb.Datum, s.SpotID, b.Sat" ;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
        $kampanja = $result['rows'][0]['Kampanja'];

        $cenaUkupno = $result['rows'][0]['CenaUkupno'];
        $popust = $result['rows'][0]['Popust'];

    //Deo za priprmeu htmla
                $html = '
            <html>
                    <head>
                            <style>
                            /* Type some style rules here */
                            </style>
                    </head>
                    <body>
                            <div class="header">
                            <img src="../'.$result_radio_stanica['Logo'].'" alt="Smiley face" />
                            <!--Ovde ide heder izvesaja--></div>
                            <hr>
                            <div class="content">
                                <!-- Type some HTML here -->
                                <h1 style="color: green; font-size: 20px;">Media plan za '.$kampanja.'</h1>
                                <table>';
                $dateOne = '1900-01-01';
                $ukupnoSekundi = 0;
                for ($i =0; $i < $result['numRows']; $i++) { 
                    if ($dateOne != $result['rows'][$i]['Datum']) {
                        if ($i != 0) {
                            $html .= '<tr><td></td></tr>';
                            $html .= '<tr><td><hr></td></tr>';
                            $html .= '<tr><td></td></tr>';
                        }
                        $dateOne = $result['rows'][$i]['Datum'];
                        $html .= '<tr><td><b>'.$dateOne.'</b></td></tr>';
                        $html .= '<tr><td></td></tr>';
                    }
                    $satOd = $result['rows'][$i]['SatOd'];
                    $satDo = $result['rows'][$i]['SatDo'];
                    $kampanja = $result['rows'][$i]['Kampanja'];
                    $spot = $result['rows'][$i]['Spot'];
                    $trajanje = $result['rows'][$i]['Trajanje'];
                    $ukupnoSekundi += $trajanje;
                    $html .= '<tr><td>'.$satOd.' - '.$satDo.' - '.$spot.' - '.$trajanje.'</td></tr>';
                            
                    $html .= '<tr>';
                    $html .= '<td>'.$result['rows'][$i]['Klijent'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['Komercijalista'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['Vrednost'].'</td>';
                    $html .= ' <td>'.$result['rows'][$i]['DatumPonude'].'</td>
                        <td>'.$result['rows'][$i]['Status'].'</td>
                        <td>'.$result['rows'][$i]['Delatnost'].'</td>';
                    $html .= 
                    '</tr>';
                }

                if($popust>0){
                    $cena="Cena: ".round(($cenaUkupno*100/(100-$popust)),2)."-".$popust."%=".$cenaUkupno."&#8364;";
                }else{
                    $cena="Cena: ".$cenaUkupno."&#8364;";
                }



                $html .= '</table>
                            </div>
                            <hr>
                            <div class="footer">Broj emitovanja:'.$i.'</div>
                            <div class="footer">Ukupno sekundi:'.$ukupnoSekundi.'</div>
                            <div class="footer">'.$cena.'</div>



                    </body>
                </html>';

/*
        echo $html;
        exit;*/

        return $html;       
    }
    //Funkcija za Finansijski izvestaj
    public function GetKampanjaHtml($kampanjaID) {
        $query = "select 
                    K.KampanjaID,
                    K.Naziv as Naziv,
                    KL.Naziv as Klijent,
                    DATE_FORMAT(K.DatumPocetka,'%d.%m.%Y') as DatumPocetka,
                    DATE_FORMAT(K.DatumKraja,'%d.%m.%Y') as DatumKraja,
                    K.Ucestalost,
                    F.Naziv as FinansijskiStatus,
                    A.Naziv as Agencija,
                    concat_ws(' ', K1.Ime, K1.Prezime) as KorisnikUneo,
                    K.PrilogIzjava,
                    K.UkupnoSekundi,
                    K.GratisSekunde,
                    K.CenaUkupno,
                    K.CenaKonacno,
                    D.Naziv as Delatnost,
                    KN.Naziv as NacinPlacanja,
                    S.Naziv as Status,
                    K.SpotUkupno as  SpotTrajanje,
                    K.RedosledUBloku,
                    K.VremeZaPotvrdu,
                    K.VremePostavke,
                    K.VremePotvrde,
                    concat_ws(' ', K2.Ime, K2.Prezime) as KorisnikPotvrda
                    from kampanja as K
                    left outer join finansijskistatuskampanja as F on K.FinansijskiStatusID = F.FinansijskiStatusKampanjaID
                    left outer join korisnik as K1 on K.KorisnikID = K1.KorisnikID
                    left outer join agencija as A on K.AgencijaID = A.AgencijaID
                    left outer join klijent as KL on K.KlijentID = KL.KlijentID
                    left outer join delatnost as D on K.DelatnostID = D.DelatnostID
                    left outer join korisnik as K2 on K.KorisnikPotvrdaID = K2.KorisnikID
                    left outer join kampanjanacinplacanja as KN on K.KampanjaNacinPlacanjaID = KN.KampanjaNacinPlacanjaID
                    left outer join statuskampanja as S on K.StatusKampanjaID = S.StatusKampanjaID
                    where K.KampanjaID = ".$kampanjaID;
        
        $query1 = "select 
                    K.CenaEmitovanja as Cena,
                    DATE_FORMAT(K.Datum,'%d.%m.%Y') as Datum,
                    DATE_FORMAT(B.VremeStart,'%H:%i:%s') as Od,
                    DATE_FORMAT(B.VremeEnd,'%H:%i:%s') as Do
                    from kampanjablok as K
                    left outer join blok as B on K.BlokID = B.BlokID
                    where K.KampanjaID = ".$kampanjaID;
        
        
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        $result1 = $dbBroker->selectManyRows($query1);
        
        $html = '<html>
                    <head>
                            <style>
                            /* Type some style rules here */
                            </style>
                    </head>
                    <body>
                            <div class="header">
                            <img src="logo.PNG" alt="Smiley face" />
                            <!--Ovde ide heder izvesaja--></div>
                            <hr>
                            <div class="content">
                            <div class="title">Predlozena kamapnja</div>
                            <div calss="table">
                                <table border="1" bordercolor="#000000">
                                    <tr>
                                            <td width="100"><b>Datum</b></td>
                                            <td width="200"><b>U periodu od - do</b></td>
                                            <td width="100"><b>Cena</b></td>
                                    </tr>';
        foreach ($result1['rows'] as $row) {
            $tr = '';
            $tr .= '<tr><td>'.$row['Datum'].'</td><td>'.$row['Od'].' - '.$row['Do'].'</td><td>'.$row['Cena'].'</td></tr>';
            $html .= $tr;
        }
        $html .='
                                </table>
                            <div class="data">
                                <p>Ukupna cena predlozene kampanje je: '.$result['CenaUkupno'].'</p>
                                <p>Trajanje predlozenog spota je '.$result['SpotTrajanje'].' sekundi</p>
                            </div>
                            </div>
                            </div>
                    </body>
                 </html>';
        
        return $html;
    }




    //Funkcija za Finansijski izvestaj
    public function GetFakturaHTML($kampanjaID) {
        $query = "select * from fakturaizvestaj where KampanjaID = ".$kampanjaID." ORDER BY Datum, SpotID, BlokID";

        $query1 = "SELECT
k.*,
a.Naziv AS AgencijaNaziv,
kl.Naziv AS KlijentNaziv
FROM
kampanja AS k
INNER JOIN agencija AS a ON k.AgencijaID = a.AgencijaID
INNER JOIN klijent AS kl ON k.KlijentID = kl.KlijentID
WHERE
k.KampanjaID = ".$kampanjaID;


        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
        $result1 = $dbBroker->selectOneRow($query1);

        $html = '<html>
                    <head>
                            <style>
                            /* Type some style rules here */
                            </style>
                    </head>
                    <body>
                            <div class="header">
                            <img src="logo.PNG" alt="Smiley face" />
                            <!--Ovde ide heder izvesaja--></div>
                            <hr>
                            <div class="content">
                            <div class="title">FAKTURA</div>
                            <div class="title">Kampanja ('.$result1['Naziv'].')</div>
                            <div class="title">Agencija ('.$result1['AgencijaNaziv'].')</div>
                            <div class="title">Klijent ('.$result1['KlijentNaziv'].')</div>
                            <div class="table">
                                <table border="1" bordercolor="#000000">
                                    <tr>
                                            <td width="100"><b>Datum</b></td>
                                            <td width="200"><b>Spot</b></td>
                                            <td width="200"><b>Sat</b></td>
                                            <td width="100"><b>Cena</b></td>
                                    </tr>';



        $datumOld="";
        $spotID_Old="";
        foreach ($result['rows'] as $row) {

            $html .= '<tr>';

            if($datumOld!=$row['Datum']){
                $html .= '<td rowspan='.$row['CountDatum'].'>'.$row['Datum'].'</td>';
                $html .= '<td rowspan='.$row['CountSpot'].'>'.$row['SpotName']." (".$row['SpotTrajanje']."s)".'</td>';
            }elseif($spotID_Old!=$row['SpotID']){
                $html .= '<td rowspan='.$row['CountSpot'].'>'.$row['SpotName']." (".$row['SpotTrajanje']."s)".'</td>';
            }



            $html .= '<td>'.$this->getSatFromBlok($row['BlokID']).'</td><td>'.$row['CenaEmitovanja'].'</td>';

            $html .= '</tr>';


            $spotID_Old=$row['SpotID'];
            $datumOld=$row['Datum'];



        }

        $html .='
               <tr><td colspan="3">Ukupno:</td><td>'.$result1['CenaUkupno'].'</td></tr>
          </table>

                            </div>
                            </div>
                    </body>
                 </html>';

        return $html;
    }



    //Funkcija za Finansijski izvestaj
    public function GetFakturaHTML_2($rows,$kurs) {



        
        $today = new DateTime(date('Y-m-d'));





        $di = new DateInterval('P1D');
        $di->invert = 1; // Proper negative date interval
        $end = clone $today;
        $end->add($di);
        $start = clone $end;
        while((int)$start->format('d')!=1){
            $start->add($di);
        }

        $dbBroker = new CoreDBBroker();



        $klijent_id = 0+ $rows[0]['KlijentID'];
        $query = "select * from klijent where KlijentID = ".$klijent_id;
        $result_klijent = $dbBroker->selectOneRow($query);

        $agencija_id = 0+ $rows[0]['AgencijaID'];
        $query = "select * from agencija where AgencijaID = ".$agencija_id;
        $result_agencija = $dbBroker->selectOneRow($query);




        $html_inner="";
        $suma=0;
        foreach($rows as $row){



            $datumPocetka = DateTime::createFromFormat('Y-m-d H:i:s', $row['DatumPocetka']);
            $datumKraja = DateTime::createFromFormat('Y-m-d H:i:s', $row['DatumKraja']);


            if(!isset($datumPocetka_real) || !isset($datumKraja_real)){
                $datumPocetka_real = clone $datumPocetka;
                $datumKraja_real = clone $datumKraja;

            }else{
                if($datumPocetka<$datumPocetka_real){
                    $datumPocetka_real = clone $datumPocetka;
                }
                if($datumKraja>$datumKraja_real){
                    $datumKraja_real = clone $datumKraja;
                }
            }



            $radioStanicaID = 0+ $row['RadioStanicaID'];

            $radioStanicaLogo = $row['RadioStanicaLogo'];




            $kampanjaID = 0+ $row['KampanjaID'];
            $query = "select * from kampanjablok where RadioStanicaID = ".$radioStanicaID. " AND KampanjaID = ".$kampanjaID." AND Datum>= '".$start->format("Y-m-d")."' AND Datum <='".$end->format("Y-m-d")."'";

//echo "<br><br><br>".$query."<br><br><br>";




            $result2 = $dbBroker->selectManyRows($query);

            $ukupno=0;
            foreach ($result2['rows'] as $row2) {

                $ukupno+=$row2['CenaEmitovanja'];
            }

            $popust = 0+$row['Popust'];
            $ukupno=$ukupno*100/(100-$popust);

$html_inner.='
                    <tr>
                        <td width="200">'.$row['RadioStanicaNaziv'].'</td>
                        <td width="100" style="text-align:right;">'.sprintf ("%.2f",round($ukupno,2)).'</td>
                        <td width="100" style="text-align:right;">'.sprintf ("%.2f",round($ukupno*$kurs,2)).'</td>
                        <td width="100" style="text-align:center;">'.$popust.'%</td>
                        <td width="100" style="text-align:right;">'.sprintf ("%.2f",round($ukupno*$kurs*(100-$popust)/100,2)).'</td>
                    </tr>
';

            $suma+=$ukupno*$kurs*(100-$popust)/100;
        }


        if($start<$datumPocetka_real){
            $start= clone $datumPocetka_real;
        }

        if($end>$datumKraja_real){
            $end= clone $datumKraja_real;
        }








/*

        $query = "SELECT
            radiostanica.Naziv,
            radiostanica.Adresa,
            radiostanica.Logo
            FROM
            kampanja
            INNER JOIN radiostanica ON kampanja.RadioStanicaID = radiostanica.RadioStanicaID
            WHERE
            kampanja.KampanjaID = ". $campaigneID;

        $dbBroker = new CoreDBBroker();
        $result_radio_stanica = $dbBroker->selectOneRow($query);
        */














        $html = '
<html>
    <head>
        <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    </head>
    <body>
        <div class="header">
            <img src="../'.$radioStanicaLogo.'" alt="Smiley face" />
        </div>
        <hr>
        <div class="content">
            <div class="table">
                <table border="0" bordercolor="#000000">
                    <tr>
                        <td width="150"><b>KLIKENT:</b></td>
                        <td width="600">'.$result_klijent['Naziv'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>ADRESA:</b></td>
                        <td width="600">'.$result_klijent['Adresa'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>PIB:</b></td>
                        <td width="600">'.$result_klijent['Pib'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>MIB:</b></td>
                        <td width="600">'.$result_klijent['MaticniBroj'].'</td>
                    </tr>
                </table>
            </div>

            <div class="title" style="text-align:center;font-size:30px;font-weight:bold;line-height:30px;width:650px;margin-top:30px;margin-bottom:20px;">NALOG ZA FAKTURU</div>

            <div class="table">
                <table border="0" bordercolor="#000000">
                    <tr>
                        <td width="150"><b>OPIS USLUGE:</b></td>
                        <td width="600">REKLAMNA KAMPANJA NA RADIJU</td>
                    </tr>
                    <tr>
                        <td width="150"><b>AGENCIJA:</b></td>
                        <td width="600">'.$result_agencija['Naziv'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>KLIJENT:</b></td>
                        <td width="600">'.$result_klijent['Naziv'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>KAMPANJA:</b></td>
                        <td width="600">'.$rows[0]['Naziv'].'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>PERIOD KAMPANJE:</b></td>
                        <td width="600">'.$datumPocetka_real->format("d/m/Y") ." - ".$datumKraja_real->format("d/m/Y").'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>PERIOD OBRAČUNA:</b></td>
                        <td width="600">'.$start->format("d/m/Y") ." - ".$end->format("d/m/Y").'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>KURS:</b></td>
                        <td width="600">'.$kurs.'</td>
                    </tr>
                    <tr>
                        <td width="150"><b>VALUTA:</b></td>
                        <td width="600">30 DANA</td>
                    </tr>
                </table>
            </div>

            <div class="table" style="margin-top:40px;">
                <table border="1" bordercolor="#000000" cellspacing="0" cellpadding="3">
                    <tr>
                        <td width="200"  style="text-align:center;"><b>Cena kampanje</b></td>
                        <td width="100"  style="text-align:center;"><b>€</b></td>
                        <td width="100"  style="text-align:center;"><b>Din.</b></td>
                        <td width="100"  style="text-align:center;"><b>Rabat</b></td>
                        <td width="100"  style="text-align:center;"><b>Sa popustom</b></td>
                    </tr>
                        '. $html_inner .'
                    <tr>
                        <td colspan="4">Ukupno:</td>
                        <td width="100" style="text-align:right;">'. sprintf ("%.2f",round($suma,2)) .'</td>
                    </tr>
                </table>

            </div>
        </div>
    </body>
</html>
';

        return $html;
    }







    public function getSatFromBlok($blokID){

        $query = "select Sat from blok WHERE BlokID =$blokID";

        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);

        return $result['Sat'];


    }


    /*
    public function ReportCommunication($filter) {
         $query = "select 
                    K.Naziv as Klijent,
                    concat_ws(' ', KO.Ime, KO.Prezime) as Komercijalista,
                    DATE_FORMAT(I.DatumKomunikacije,'%d.%m.%Y') as DatumKomunikacija,
                    T.Naziv as TipKomunikacija,
                    D.Naziv as Delatnost,
                    I.PrilogLink,
                    I.PrilogNaziv
                    from istorijakomunikacija I
                    inner join klijent K on I.KlijentID = K.KlijentID
                    left outer join delatnost D on K.DelatnostID = D.DelatnostID
                    inner join korisnik KO on I.KorisnikID = KO.KorisnikID
                    left outer join  tipkomunikacija T on I.TipKomunikacijaID = T.TipKomunikacijaID
                    where ('$filter->klijentID' = '' or I.KlijentID = '$filter->klijentID')
                    and('$filter->tipKomunikacijaID' = '' or T.TipKomunikacijaID = '$filter->tipKomunikacijaID')
                    and('$filter->tipUgovoraID' = '' or K.TipUgovoraID = '$filter->tipUgovoraID')
                    and('$filter->delatnostID' = '' or K.DelatnostID = '$filter->delatnostID')
                    and('$filter->komercijalistaID' = '' or KO.KorisnikID = '$filter->komercijalistaID')
                    and('$filter->datumOD' = '' or I.VremePostavke >= '$filter->datumOD')
                    and('$filter->datumDO' = '' or I.VremePostavke <= '$filter->datumDO')";
        $dbBroker = new CoreDBBroker();
        //file_put_contents("neki.txt", $query);
        $result = $dbBroker->selectManyRows($query);
    //Deo za priprmeu htmla
        $html = '
            <html>
                    <head>
                            <style type="text/css">
                            body
                            {
                            font-size:12px;
                            }
                            
                            </style>
                    </head>
                    <body>
                            <div class="header">
                            <img src="logo.PNG" alt="Smiley face" />
                            Ovde ide heder izvesaja</div>
                            <hr>
                            <div class="content">
                                <!-- Type some HTML here -->
                                <h1 style="color: green; font-size: 20px;">Test naslov izvestaja</h1>
                                <h3 style="font-size: 14px;">Test opis za izvestaj</h3>
                                <table border="1" cellspacing="0" width="100%">
                                        <tr>
                                                <td><b>Klijent</b></td>
                                                <td><b>Komercijalista</b></td>
                                                <td><b>Datum Komunikacije</b></td>
                                                <td><b>Delatnost</b></td>
                                                <td><b>TipKomunikacija</b></td>
                                                <td><b>Detalji</b></td>
                                        </tr>';
                for ($i =0; $i < $result['numRows']; $i++) {    
                    $html .= '<tr>';
                    $html .= '<td>'.$result['rows'][$i]['Klijent'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['Komercijalista'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['DatumKomunikacija'].'</td>';
                    $html .= ' <td>'.$result['rows'][$i]['Delatnost'].'</td>
                        <td>'.$result['rows'][$i]['TipKomunikacija'].'</td>
                        <td><a src='.$result['rows'][$i]['PrilogLink'].'>'.$result['rows'][$i]['PrilogNaziv'].'</a></td>';
                    $html .= 
                    '</tr>';
                }

                $html .= '</table>
                            </div>
                            <hr>
                            <div class="footer">Ovde ide futer izvesaja</div>
                    </body>
                </html>';
        return $html;       
    }
    
    public function ReportOffers($filter) {
         $query = "select 
                    K.Naziv as Klijent,
                    concat_ws(' ', KO.Ime, KO.Prezime) as Komercijalista,
                    P.Vrednost as Vrednost,
                    DATE_FORMAT(P.VremePostavke,'%d.%m.%Y') as DatumPonude,
                    S.Naziv as Status,
                    D.Naziv as Delatnost
                    from ponuda P
                    inner join klijent K on P.KlijentID = K.KlijentID
                    left outer join delatnost D on K.DelatnostID = D.DelatnostID
                    inner join korisnik KO on P.KorisnikID = KO.KorisnikID
                    inner join statusponuda S on P.StatusPonudaID = S.StatusPonudaID
                    where ('$filter->klijentID' = '' or P.KlijentID = '$filter->klijentID')
                    and('$filter->statusID' = '' or S.StatusPonudaID = '$filter->statusID')
                    and('$filter->tipUgovoraID' = '' or K.TipUgovoraID = '$filter->tipUgovoraID')
                    and('$filter->delatnostID' = '' or K.DelatnostID = '$filter->delatnostID')
                    and('$filter->datumOD' = '' or P.VremePostavke >= '$filter->datumOD')
                    and('$filter->datumDO' = '' or P.VremePostavke <= '$filter->datumDO') " ;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
    //Deo za priprmeu htmla
                $html = '
            <html>
                    <head>
                            <style>
                            /* Type some style rules here */
    /*
                            </style>
                    </head>
                    <body>
                            <div class="header">
                            <img src="logo.PNG" alt="Smiley face" />
                            Ovde ide heder izvesaja</div>
                            <hr>
                            <div class="content">
                                <!-- Type some HTML here -->
                                <h1 style="color: green; font-size: 20px;">Test naslov izvestaja</h1>
                                <h3 style="font-size: 14px;">Test opis za izvestaj</h3>
                                <table border="1">
                                        <tr>
                                                <td><b>Klijent</b></td>
                                                <td><b>Komercijalista</b></td>
                                                <td><b>Vrednost</b></td>
                                                <td><b>Datum Ponude</b></td>
                                                <td><b>Status ponude</b></td>
                                                <td><b>Delatnost</b></td>
                                        </tr>';
                for ($i =0; $i < $result['numRows']; $i++) {    
                    $html .= '<tr>';
                    $html .= '<td>'.$result['rows'][$i]['Klijent'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['Komercijalista'].'</td>';
                    $html .= '<td>'.$result['rows'][$i]['Vrednost'].'</td>';
                    $html .= ' <td>'.$result['rows'][$i]['DatumPonude'].'</td>
                        <td>'.$result['rows'][$i]['Status'].'</td>
                        <td>'.$result['rows'][$i]['Delatnost'].'</td>';
                    $html .= 
                    '</tr>';
                }

                $html .= '</table>
                            </div>
                            <hr>
                            <div class="footer">Ovde ide futer izvesaja</div>
                    </body>
                </html>';
        
        return $html;       
    }
    
    public function getFinancialTable($result) {
        $table = '<table>
                     <tr>
                        <td><b>Agencija</b></td>
                        <td><b>Klijent</b></td>
                        <td><b>Kampanja</b></td>
                        <td><b>Detalji</b></td>
                        <td class="last"><b>Cena</b></td>
                     </tr>';
        
        $agencija = '';
        $klijent = '';
        $kampanja = '';
        
        $brojAgencija = 0;
        $brojKlijenata = 0;
        $brojKampanja = 0;
        $ukupnaCena = 0;
        
        foreach ($result['rows'] as $row) {
            $classAgencija = 'empty';
            $classKlijent = 'empty';
            $classKampanja = 'empty';
            
            if($kampanja != $row['KampanjaNaziv']) {
                $classKampanja = 'kampanja';
                $kampanja = $row['KampanjaNaziv'];
                $brojKampanja++;
            }
            
            
            if($klijent != $row['KlijentNaziv']) {
                $classKlijent = 'klijent';
                $classKampanja = 'kampanja';
                $klijent = $row['KlijentNaziv'];
                $brojKlijenata++;
            }
            
            
            if($agencija != $row['AgencijaNaziv']) {
                $classAgencija = 'agencija';
                $classKlijent = 'klijent';
                $classKampanja = 'kampanja';
                $agencija = $row['AgencijaNaziv'];
                $brojAgencija++;
            }
            
            
            
            $ukupnaCena += (double) $row['CenaEmitovanja'];
            $table .= '<tr>';
                $table .= '<td class="'.$classAgencija.'">'.$row['AgencijaNaziv'].'</td>';
                $table .= '<td class="'.$classKlijent.'">'.$row['KlijentNaziv'].'</td>';
                $table .= '<td class="'.$classKampanja.'">'.$row['KampanjaNaziv'].'</td>';
                $table .= '<td>'.$row['Datum'].'-Blok-'.$row['BlokID'].'</td>';
                $table .= '<td class="last">'.$row['CenaEmitovanja'].'</td>';
            $table .= '</tr>';
        }
        
        $table .= '<tr class="last">
                    <td><b>' . $brojAgencija . '</b></td>
                    <td><b>' . $brojKlijenata . '</b></td>
                    <td><b>' . $brojKampanja . '</b></td>
                    <td><b>' . $result['numRows'] . '</b></td>
                    <td class="last"><b>' . $ukupnaCena . '</b></td>
                    </tr>
                </table>';

        return $table;
    }
    */
}

?>