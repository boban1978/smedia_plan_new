<?php
/**
 * Description of IzvestajClass
 *
 * @author n.lekic
 */
class IzvestajClass {
    //Funkcija za Finansijski izvestaj
    public function ReportFinancial_old($filter) {
        //Query za prvi izestaj
        $queryFirst = "select 
                    F.*,
                    (select count(*) from finansijskiizvestaj 
                        where AgencijaNaziv = F.AgencijaNaziv
                        and ('$filter->klijentID' = '' or KlijentID = '$filter->klijentID')
                        and('$filter->agencijaID' = '' or AgencijaID = '$filter->agencijaID')
                        and('$filter->tipUgovoraID' = '' or TipUgovoraID = '$filter->tipUgovoraID')
                        and('$filter->delatnostID' = '' or DelatnostID = '$filter->delatnostID')
                        and('$filter->datumOD' = '' or Datum >= '$filter->datumOD')
                        and('$filter->datumDO' = '' or Datum <= '$filter->datumDO')) as countAgencija,
                    (select count(*) from finansijskiizvestaj 
                        where AgencijaNaziv = F.AgencijaNaziv 
                        and KlijentNaziv = F.KlijentNaziv
                        and ('$filter->klijentID' = '' or KlijentID = '$filter->klijentID')
                        and('$filter->agencijaID' = '' or AgencijaID = '$filter->agencijaID')
                        and('$filter->tipUgovoraID' = '' or TipUgovoraID = '$filter->tipUgovoraID')
                        and('$filter->delatnostID' = '' or DelatnostID = '$filter->delatnostID')
                        and('$filter->datumOD' = '' or Datum >= '$filter->datumOD')
                        and('$filter->datumDO' = '' or Datum <= '$filter->datumDO')) as countKlijent,
                    (select count(*) from finansijskiizvestaj 
                        where AgencijaNaziv = F.AgencijaNaziv 
                        and KlijentNaziv = F.KlijentNaziv 
                        and KampanjaNaziv = F.KampanjaNaziv
                        and ('$filter->klijentID' = '' or KlijentID = '$filter->klijentID')
                        and('$filter->agencijaID' = '' or AgencijaID = '$filter->agencijaID')
                        and('$filter->tipUgovoraID' = '' or TipUgovoraID = '$filter->tipUgovoraID')
                        and('$filter->delatnostID' = '' or DelatnostID = '$filter->delatnostID')
                        and('$filter->datumOD' = '' or Datum >= '$filter->datumOD')
                        and('$filter->datumDO' = '' or Datum <= '$filter->datumDO')) as countKampanja
                    from finansijskiizvestaj F 
                    where ('$filter->klijentID' = '' or F.KlijentID = '$filter->klijentID')
                    and('$filter->agencijaID' = '' or F.AgencijaID = '$filter->agencijaID')
                    and('$filter->tipUgovoraID' = '' or F.TipUgovoraID = '$filter->tipUgovoraID')
                    and('$filter->delatnostID' = '' or F.DelatnostID = '$filter->delatnostID')
                    and('$filter->datumOD' = '' or F.Datum >= '$filter->datumOD')
                    and('$filter->datumDO' = '' or F.Datum <= '$filter->datumDO')";
        $dbBroker = new CoreDBBroker();
        //file_put_contents("neki.txt", $queryFirst);
        $resultFirst= $dbBroker->selectManyRows($queryFirst);
        if ($filter->komparativnaAnaliza == 1) {
            //Query za drugi izestaj
            $querySecond = "select 
                        F.*,
                        (select count(*) from finansijskiizvestaj 
                            where AgencijaNaziv = F.AgencijaNaziv
                            and ('$filter->klijentIDUporedno' = '' or KlijentID = '$filter->klijentIDUporedno')
                            and('$filter->agencijaIDUporedno' = '' or AgencijaID = '$filter->agencijaIDUporedno')
                            and('$filter->tipUgovoraIDUporedno' = '' or TipUgovoraID = '$filter->tipUgovoraIDUporedno')
                            and('$filter->delatnostIDUporedno' = '' or DelatnostID = '$filter->delatnostIDUporedno')
                            and('$filter->datumODUporedno' = '' or Datum >= '$filter->datumODUporedno')
                            and('$filter->datumDOUporedno' = '' or Datum <= '$filter->datumDOUporedno')) as countAgencija,
                        (select count(*) from finansijskiizvestaj 
                            where AgencijaNaziv = F.AgencijaNaziv 
                            and KlijentNaziv = F.KlijentNaziv
                            and ('$filter->klijentIDUporedno' = '' or KlijentID = '$filter->klijentIDUporedno')
                            and('$filter->agencijaIDUporedno' = '' or AgencijaID = '$filter->agencijaIDUporedno')
                            and('$filter->tipUgovoraIDUporedno' = '' or TipUgovoraID = '$filter->tipUgovoraIDUporedno')
                            and('$filter->delatnostIDUporedno' = '' or DelatnostID = '$filter->delatnostIDUporedno')
                            and('$filter->datumODUporedno' = '' or Datum >= '$filter->datumODUporedno')
                            and('$filter->datumDOUporedno' = '' or Datum <= '$filter->datumDOUporedno')) as countKlijent,
                        (select count(*) from finansijskiizvestaj 
                            where AgencijaNaziv = F.AgencijaNaziv 
                            and KlijentNaziv = F.KlijentNaziv 
                            and KampanjaNaziv = F.KampanjaNaziv
                            and ('$filter->klijentIDUporedno' = '' or KlijentID = '$filter->klijentIDUporedno')
                            and('$filter->agencijaIDUporedno' = '' or AgencijaID = '$filter->agencijaIDUporedno')
                            and('$filter->tipUgovoraIDUporedno' = '' or TipUgovoraID = '$filter->tipUgovoraIDUporedno')
                            and('$filter->delatnostIDUporedno' = '' or DelatnostID = '$filter->delatnostIDUporedno')
                            and('$filter->datumODUporedno' = '' or Datum >= '$filter->datumODUporedno')
                            and('$filter->datumDOUporedno' = '' or Datum <= '$filter->datumDOUporedno')) as countKampanja
                        from finansijskiizvestaj F 
                        where ('$filter->klijentIDUporedno' = '' or F.KlijentID = '$filter->klijentID')
                        and('$filter->agencijaIDUporedno' = '' or F.AgencijaID = '$filter->agencijaID')
                        and('$filter->tipUgovoraIDUporedno' = '' or F.TipUgovoraID = '$filter->tipUgovoraID')
                        and('$filter->delatnostIDUporedno' = '' or F.DelatnostID = '$filter->delatnostID')
                        and('$filter->datumODUporedno' = '' or F.Datum >= '$filter->datumODUporedno')
                        and('$filter->datumDOUporedno' = '' or F.Datum <= '$filter->datumDOUporedno')";
            $dbBroker = new CoreDBBroker();
            $resultSecond = $dbBroker->selectManyRows($querySecond);
        }
        
        
        
        //Deo za priprmeu htmla
        
        $html = '
            <html>
                    <head>   
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
                                <table border="1" >
                                        <tr>
                                                <td><b>Agencija</b></td>
                                                <td><b>Klijent</b></td>
                                                <td><b>Kampanja</b></td>
                                                <td><b>Detalji</b></td>
                                                <td><b>Cena</b></td>
                                        </tr>
                                        <tr>
                                                <th rowspan='.$resultFirst['rows'][0]['countAgencija'].'>'.$resultFirst['rows'][0]['AgencijaNaziv'].'</th>
                                                <th rowspan='.$resultFirst['rows'][0]['countKlijent'].'>'.$resultFirst['rows'][0]['KlijentNaziv'].'</th>
                                                <th rowspan='.$resultFirst['rows'][0]['countKampanja'].'>'.$resultFirst['rows'][0]['KampanjaNaziv'].'</th>
                                                <td>'.$resultFirst['rows'][0]['Datum'].'-Blok-'.$resultFirst['rows'][0]['BlokID'].'</td>
                                                <td>'.$resultFirst['rows'][0]['CenaEmitovanja'].'</td>
                                        </tr>';
                $temp_agencija = $resultFirst['rows'][0]['AgencijaNaziv'];
                $temp_klijent = $resultFirst['rows'][0]['KlijentNaziv'];
                $temp_kampanja = $resultFirst['rows'][0]['KampanjaNaziv'];
                
                //Privrmene promenljive za zadnji red koji predstavlja SUM
                $brojAgencija = 1;
                $brojKlijent = 1;
                $brojKampanja = 1;
                $ukupnaCena = $resultFirst['rows'][0]['CenaEmitovanja'];
                for ($i =1; $i < $resultFirst['numRows']; $i++) {    
                    $html .= '<tr>';
                    if ($resultFirst['rows'][$i]['AgencijaNaziv'] <> $temp_agencija) {
                        $html .= '<th rowspan='.$resultFirst['rows'][$i]['countAgencija'].'>'.$resultFirst['rows'][$i]['AgencijaNaziv'].'</th>';
                        $html .= '<th rowspan='.$resultFirst['rows'][$i]['countKlijent'].'>'.$resultFirst['rows'][$i]['KlijentNaziv'].'</th>';
                        $html .= '<th rowspan='.$resultFirst['rows'][$i]['countKampanja'].'>'.$resultFirst['rows'][$i]['KampanjaNaziv'].'</th>';
                    } else {
                        if  ($resultFirst['rows'][$i]['KlijentNaziv'] <> $temp_klijent) {
                            $html .= '<th rowspan='.$resultFirst['rows'][$i]['countKlijent'].'>'.$resultFirst['rows'][$i]['KlijentNaziv'].'</th>';
                            $html .= '<th rowspan='.$resultFirst['rows'][$i]['countKampanja'].'>'.$resultFirst['rows'][$i]['KampanjaNaziv'].'</th>';
                        } else {
                            if ($resultFirst['rows'][$i]['KampanjaNaziv'] <> $temp_kampanja) {
                                $html .= '<th rowspan='.$resultFirst['rows'][$i]['countKampanja'].'>'.$resultFirst['rows'][$i]['KampanjaNaziv'].'</th>';
                            }
                        }
                    }
                    $html .= ' <td>'.$resultFirst['rows'][$i]['Datum'].'-Blok-'.$resultFirst['rows'][$i]['BlokID'].'</td>
                        <td>'.$resultFirst['rows'][$i]['CenaEmitovanja'].'</td>
                    </tr>';
                    if ($resultFirst['rows'][$i]['AgencijaNaziv'] <> $temp_agencija) {
                        $brojAgencija++;
                    }
                    if ($resultFirst['rows'][$i]['KlijentNaziv'] <> $temp_klijent) {
                        $brojKlijent++;
                    } 
                    if ($resultFirst['rows'][$i]['KampanjaNaziv'] <> $temp_kampanja) {
                        $brojKampanja++;
                    }
                    $ukupnaCena += $resultFirst['rows'][$i]['CenaEmitovanja'];
                    $temp_agencija = $resultFirst['rows'][$i]['AgencijaNaziv'];
                    $temp_klijent = $resultFirst['rows'][$i]['KlijentNaziv'];
                    $temp_kampanja = $resultFirst['rows'][$i]['KampanjaNaziv'];
                }

                $html .= '<tr>
                                <td><b>'.$brojAgencija.'</b></td>
                                <td><b>'.$brojKlijent.'</b></td>
                                <td><b>'.$brojKampanja.'</b></td>
                                <td><b>'.$resultFirst['numRows'].'</b></td>
                                <td><b>'.$ukupnaCena.'</b></td>
                          </tr>
                        </table>
                        </div>
                        <hr>
                        <div class="footer">Ovde ide futer izvesaja</div>
                    </body>
                </html>';
        
        return $html;
    }









    //Funkcija za Finansijski izvestaj
    public function ReportFinancial($filter,$type="html") {



        $query = "SELECT
a.Naziv AS AgencijaNaziv,
kl.Naziv AS KlijentNaziv,
k.Naziv AS KampanjaNaziv,
k.DatumPocetka AS DatumPocetka,
k.DatumKraja AS DatumKraja,
k.DelatnostID AS DelatnostID,
d.Naziv AS DelatnostNaziv,
Sum(kb.CenaEmitovanja) AS CenaEmitovanja,
k.KlijentID AS KlijentID,
k.AgencijaID AS AgencijaID,
Count(kb.BlokID) AS BrojEmitovanja,
k.KorisnikID AS KorisnikID,
ko.Ime AS KorisnikIme,
k.RadioStanicaID AS RadioStanicaID,
radiostanica.Naziv AS RadioStanicaNaziv,
k.KampanjaID AS KampanjaID,
k.Popust AS Popust,
sum(spot.SpotTrajanje) AS SpotTrajanje,
finansijskistatuskampanja.Naziv AS FinansijskiStatus

from ((((((`kampanjablok` `kb`
join `kampanja` `k` on((`kb`.`KampanjaID` = `k`.`KampanjaID`)))
join `klijent` `kl` on(((`kl`.`KlijentID` = `k`.`KlijentID`) and (`kl`.`Aktivan` = 1))))
join `delatnost` `d` on((`kl`.`DelatnostID` = `d`.`DelatnostID`)))
left join `agencija` `a` on(((`a`.`Aktivan` = 1) and (`k`.`AgencijaID` = `a`.`AgencijaID`))))
join `korisnik` `ko` on((`ko`.`KorisnikID` = `k`.`KorisnikID`)))
join `radiostanica` on((`radiostanica`.`RadioStanicaID` = `k`.`RadioStanicaID`)))
INNER JOIN spot ON kb.SpotID = spot.SpotID
INNER JOIN finansijskistatuskampanja ON finansijskistatuskampanja.FinansijskiStatusKampanjaID = k.FinansijskiStatusID



         where ('$filter->radioStanicaID' = '' or k.RadioStanicaID = '$filter->radioStanicaID')

        and ('$filter->klijentID' = '' or `k`.`KlijentID` = '$filter->klijentID')
        and('$filter->agencijaID' = '' or `k`.`AgencijaID` = '$filter->agencijaID')
        and('$filter->delatnostID' = '' or k.DelatnostID = '$filter->delatnostID')
        and('$filter->komercijalistaID' = '' or ko.KorisnikID = '$filter->komercijalistaID')
        and('$filter->datumOD' = '' or kb.Datum >= '$filter->datumOD')
        and('$filter->datumDO' = '' or kb.Datum <= '$filter->datumDO')

GROUP BY
k.KampanjaID,
d.DelatnostID,
k.AgencijaID,
k.KlijentID,
k.DatumPocetka,
k.DatumKraja,
k.KorisnikID
ORDER BY
AgencijaNaziv ASC,
KlijentNaziv ASC,
KampanjaNaziv ASC
        ";


        $dbBroker = new CoreDBBroker();
        //file_put_contents("neki.txt", $queryFirst);
        $result= $dbBroker->selectManyRows($query);

        //Deo za priprmeu htmla




        $con="";
        switch ($type) {
            case 'html':
                $broj_emitovanja = 0;
                $ukupno_sekundi = 0;
                $cena_emitovanja_bez_popusta = 0;
                $cena_emitovanja = 0;

                foreach ($result['rows'] as $row) {

                    $broj_emitovanja += $row['BrojEmitovanja'];
                    $ukupno_sekundi += $row['SpotTrajanje'];
                    $cena_emitovanja_bez_popusta += $row['CenaEmitovanja'] * 100 / (100 - $row['Popust']);
                    $cena_emitovanja += $row['CenaEmitovanja'];

                    $con .= '<tr style="background-color: #FFFFCB;" >
                                <td>' . $row['AgencijaNaziv'] . '</td>
                                <td>' . $row['KlijentNaziv'] . '</td>
                                <td>' . $row['RadioStanicaNaziv'] . '</td>
                                <td>' . $row['KampanjaNaziv'] . '</td>
                                <td>' . date("d/m/Y", strtotime($row['DatumPocetka'])) . '</td>
                                <td>' . date("d/m/Y", strtotime($row['DatumKraja'])) . '</td>
                                <td>' . $row['DelatnostNaziv'] . '</td>
                                <td style="text-align:center;">' . $row['BrojEmitovanja'] . '</td>
                                <td style="text-align:center;">' . $row['SpotTrajanje'] . '</td>
                                <td style="text-align:right;">' . round($row['CenaEmitovanja'] * 100 / (100 - $row['Popust']), 2) . '</td>
                                <td style="text-align:center;">' . $row['Popust'] . '%</td>
                                <td style="text-align:right;">' . round($row['CenaEmitovanja'], 2) . '</td>
                                <td>' . $row['FinansijskiStatus'] . '</td>
                                <td>' . $row['KorisnikIme'] . '</td>
                          </tr>
                       ';

                }


                $html = '
<html>
    <head>
    </head>
    <body>
        <div class="header">
        <img src="' . HOME_ADDRESS . 'logo.png" alt="Smiley face" />
        </div>
        <hr>
        <div class="content">
            <!-- Type some HTML here -->
            <h1 style="color: green; font-size: 20px;">Finansijski izveštaj</h1>
            <!--<h3 style="font-size: 14px;">Kriterijumi izveštaja:</h3>-->

            <table border="1" style="font-size: 13px;" >
                <tr style="background-color: rgb(255, 232, 199);" >
                        <td>Agencija</td>
                        <td>Klijent</td>
                        <td>Radio Stanica</td>
                        <td>Kampanja</td>
                        <td>Datum od</td>
                        <td>Datum do</td>
                        <td>Delatnost</td>
                        <td>Broj Emitovanja</td>
                        <td>Ukupno Sekundi</td>
                        <td>Cena</td>
                        <td>Popust</td>
                        <td>Cena sa popustom</td>
                        <td>Status</td>
                        <td>Korisnik</td>
                </tr>
                ' . $con . '
                <tr style="    background-color: #DDFFCB;" >
                    <td colspan="7">' . 'Ukupno:' . '</td>
                    <td style="text-align:center;">' . $broj_emitovanja . '</td>
                    <td style="text-align:center;">' . $ukupno_sekundi . '</td>
                    <td style="text-align:right;">' . round($cena_emitovanja_bez_popusta, 2) . '</td>
                    <td>' . '' . '</td>
                    <td style="text-align:right;">' . round($cena_emitovanja, 2) . '</td>
                    <td colspan="2">' . '' . '</td>
                  </tr>
            </table>
        </div>
        <hr>
        <div class="footer">Ovde ide futer izvesaja</div>
    </body>
</html>';

                return $html;


                break;
            case 'excel':

                $fname = tempnam("/tmp", "finansijski_izvestaj.xls");

                $workbook = &new writeexcel_workbook($fname);
                $worksheet = &$workbook->addworksheet();

# The general syntax is write($row, $column, $token). Note that row and
# column are zero indexed
#

# Write some text

                $format1 =& $workbook->addFormat();
                $format1->set_bg_color('45');
                $format1->set_border('1');

                $format2 =& $workbook->addFormat();
                $format2->set_bg_color('26');
                $format2->set_border('1');

                $format3 =& $workbook->addFormat();
                $format3->set_bg_color('42');
                $format3->set_border('1');


                $length_arr=array();
                for($j=0;$j<14;$j++){
                    $length_arr[$j]=0;
                }

                $value=array();
                $value[0]='Agencija';
                $value[1]='Klijent';
                $value[2]='Radio Stanica';
                $value[3]='Kampanja';
                $value[4]='Datum od';
                $value[5]='Datum do';
                $value[6]='Delatnost';
                $value[7]='Broj Emitovanja';
                $value[8]='Ukupno Sekundi';
                $value[9]='Cena';
                $value[10]='Popust [%]';
                $value[11]='Cena sa popustom';
                $value[12]='Status';
                $value[13]='Korisnik';

                for($j=0;$j<14;$j++){
                    $length_arr[$j]=max(strlen($value[$j]),$length_arr[$j]);
                    $worksheet->write(0, $j, $value[$j], $format1);
                }

                $i = 0;

                $broj_emitovanja = 0;
                $ukupno_sekundi = 0;
                $cena_emitovanja_bez_popusta = 0;
                $cena_emitovanja = 0;

                foreach ($result['rows'] as $row) {
                    $i++;
                    $broj_emitovanja += $row['BrojEmitovanja'];
                    $ukupno_sekundi += $row['SpotTrajanje'];
                    $cena_emitovanja_bez_popusta += $row['CenaEmitovanja'] * 100 / (100 - $row['Popust']);
                    $cena_emitovanja += $row['CenaEmitovanja'];

                    $value[0]=$row['AgencijaNaziv'];
                    $value[1]=$row['KlijentNaziv'];
                    $value[2]=$row['RadioStanicaNaziv'];
                    $value[3]=$row['KampanjaNaziv'];
                    $value[4]=date("d/m/Y", strtotime($row['DatumPocetka']));
                    $value[5]=date("d/m/Y", strtotime($row['DatumKraja']));
                    $value[6]=$row['DelatnostNaziv'];
                    $value[7]=$row['BrojEmitovanja'];
                    $value[8]=$row['SpotTrajanje'];
                    $value[9]=round($row['CenaEmitovanja'] * 100 / (100 - $row['Popust']), 2);
                    $value[10]=$row['Popust'];
                    $value[11]=round($row['CenaEmitovanja'], 2);
                    $value[12]=$row['FinansijskiStatus'];
                    $value[13]=$row['KorisnikIme'];

                    for($j=0;$j<14;$j++){
                        $length_arr[$j]=max(strlen($value[$j]),$length_arr[$j]);
                        $worksheet->write($i, $j, $value[$j],$format2);
                    }
                }

                $i++;

                $worksheet->merge_cells($i,0,$i,6);
                $worksheet->merge_cells($i,12,$i,13);

                $value[0]='Ukupno:';
                $value[1]='';
                $value[2]='';
                $value[3]='';
                $value[4]='';
                $value[5]='';
                $value[6]='';
                $value[7]=$broj_emitovanja;
                $value[8]=$ukupno_sekundi;
                $value[9]=round($cena_emitovanja_bez_popusta, 2);
                $value[10]='';
                $value[11]=round($cena_emitovanja, 2);
                $value[12]='';
                $value[13]='';

                for($j=0;$j<14;$j++){
                    $length_arr[$j]=max(strlen($value[$j]),$length_arr[$j]);
                    $worksheet->write($i, $j, $value[$j], $format3);
                }

                for($j=0;$j<14;$j++){
                    $worksheet->set_column($j,$j,$length_arr[$j]*1.2);
                }

                $workbook->close();

                header("Content-Type: application/x-msexcel; name=\"finansijski_izvestaj.xls\"");
                header("Content-Disposition: inline; filename=\"finansijski_izvestaj.xls\"");
                $fh = fopen($fname, "rb");
                fpassthru($fh);
                unlink($fname);


                if ($filter->download_token > 0) {
                    setcookie("download_token", $filter->download_token, time() + (86400 * 30), "/");
                    //echo $_COOKIE['rand']."hghh";
                }else{
                    unset($_COOKIE['download_token']);
                    setcookie('download_token', null, -1, '/');
                    //echo "nooooo";
                }


        break;
            case 'pdf':
                $broj_emitovanja = 0;
                $ukupno_sekundi = 0;
                $cena_emitovanja_bez_popusta = 0;
                $cena_emitovanja = 0;

                foreach ($result['rows'] as $row) {

                    $broj_emitovanja += $row['BrojEmitovanja'];
                    $ukupno_sekundi += $row['SpotTrajanje'];
                    $cena_emitovanja_bez_popusta += $row['CenaEmitovanja'] * 100 / (100 - $row['Popust']);
                    $cena_emitovanja += $row['CenaEmitovanja'];

                    $con .= '<tr style="background-color: #FFFFCB;" >
                                <td>' . $row['AgencijaNaziv'] . '</td>
                                <td>' . $row['KlijentNaziv'] . '</td>
                                <td>' . $row['RadioStanicaNaziv'] . '</td>
                                <td>' . $row['KampanjaNaziv'] . '</td>
                                <td>' . date("d/m/Y", strtotime($row['DatumPocetka'])) . '</td>
                                <td>' . date("d/m/Y", strtotime($row['DatumKraja'])) . '</td>
                                <td>' . $row['DelatnostNaziv'] . '</td>
                                <td style="text-align:center;">' . $row['BrojEmitovanja'] . '</td>
                                <td style="text-align:center;">' . $row['SpotTrajanje'] . '</td>
                                <td style="text-align:right;">' . round($row['CenaEmitovanja'] * 100 / (100 - $row['Popust']), 2) . '</td>
                                <td style="text-align:center;">' . $row['Popust'] . '%</td>
                                <td style="text-align:right;">' . round($row['CenaEmitovanja'], 2) . '</td>
                                <td>' . $row['FinansijskiStatus'] . '</td>
                                <td>' . $row['KorisnikIme'] . '</td>
                          </tr>
                       ';

                }


                $html = '
<html>
    <head>
    </head>
    <body>
        <div class="header">
        <img src="' . HOME_ADDRESS . 'logo.png" alt="Smiley face" />
        </div>
        <hr>
        <div class="content">
            <!-- Type some HTML here -->
            <h1 style="color: green; font-size: 20px;">Finansijski izveštaj</h1>
            <!--<h3 style="font-size: 14px;">Kriterijumi izveštaja:</h3>-->

            <table border="1" style="font-size: 9px;font-weight:10;" cellspacing="0" cellpadding="0">
                <tr style="background-color: rgb(255, 232, 199);" >
                        <td>Agencija</td>
                        <td>Klijent</td>
                        <td>Radio Stanica</td>
                        <td>Kampanja</td>
                        <td>Datum od</td>
                        <td>Datum do</td>
                        <td>Delatnost</td>
                        <td>Broj Emitovanja</td>
                        <td>Ukupno Sekundi</td>
                        <td>Cena</td>
                        <td>Popust</td>
                        <td>Cena sa popustom</td>
                        <td>Status</td>
                        <td>Korisnik</td>
                </tr>
                ' . $con . '
                <tr style="background-color: #DDFFCB;" >
                    <td colspan="7">' . 'Ukupno:' . '</td>
                    <td style="text-align:center;">' . $broj_emitovanja . '</td>
                    <td style="text-align:center;">' . $ukupno_sekundi . '</td>
                    <td style="text-align:right;">' . round($cena_emitovanja_bez_popusta, 2) . '</td>
                    <td>' . '' . '</td>
                    <td style="text-align:right;">' . round($cena_emitovanja, 2) . '</td>
                    <td colspan="2">' . '' . '</td>
                  </tr>
            </table>
        </div>
        <hr>
        <div class="footer">Ovde ide futer izvesaja</div>
    </body>
</html>';

                return $html;


                break;
            default:

        }




    }









    
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
                    and('$filter->komercijalistaID' = '' or KO.KorisnikID = '$filter->komercijalistaID')
                    and('$filter->datumOD' = '' or P.VremePostavke >= '$filter->datumOD')
                    and('$filter->datumDO' = '' or P.VremePostavke <= '$filter->datumDO')
                    and('$filter->korisnikID' = '' or P.KorisnikID = '$filter->korisnikID')" ;

        //send_respons_boban($query);



        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
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
    
    public function MediaPlanForCampaigne($campaigneID) {
         $query = "select kb.Datum, 
                    k.Naziv as Kampanja, 
                    s.SpotName as Spot, 
                    concat(s.SpotTrajanje, ' s') as Trajanje, 
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












}

?>