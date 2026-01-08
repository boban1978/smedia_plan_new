<?php

/**
 * Description of KampanjaClass
 *
 * @author n.lekic
 */
class KampanjaClass {

    public function KampanjaGetForComboBox(Kampanja $kampanja) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($kampanja);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:' . json_encode($result['rows']));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;
    }

    public function KampanjaLoad(Kampanja $kampanja) {

        $query = "select 
                    K.KampanjaID as kampanjaID,
                    K.RadioStanicaID as radioStanicaID,
                    K.Naziv as naziv,
                    K.KlijentID as klijentID,
                    K.DatumPocetka as datumPocetka,
                    K.DatumKraja as datumKraja,
                    K.FinansijskiStatusID as finansijskiStatusID,
                    K.KampanjaNacinPlacanjaID as kampanjaNacinPlacanjaID,
                    K.StatusKampanjaID as statusKampanjaID,
                    K.AgencijaID as agencijaID,
                    K.KorisnikID as korisnikID,
                    K.PrilogIzjava as prilogIzjava,
                    K.UkupnoSekundi as ukupnoSekundi,
                    K.GratisSekunde as gratisSekunde,
                    K.CenaUkupno as cenaUkupno,
                    K.CenaKonacno as cenaKonacno,
                    K.DelatnostID as delatnosID,
                    K.VremeZaPotvrdu as vremeZaPotvrdu,
                    K.VremePostavke as vremePostavke,
                    K.VremePotvrde as vremePotvrde,
                    K.KorisnikPotvrdaID as korisnikPotvrdaID
                    from kampanja as K
                    where K.KampanjaID = " . $kampanja->getKampanjaID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:' . json_encode($result));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija koja insertuje podatke o zahtevanoj kampanji

    public function KampanjaZahtevInsert(KampanjaZahtev $kampanjaZahtev, $ponudaId = NULL) {

        //send_respons_boban($_POST);
        //set_time_limit(0);


        /******************* UBACUJE U BAZU ZAHTEV I SPOTOVE ******************************/
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kampanjaZahtev);
        $sportArray = $kampanjaZahtev->getSpotArray();


        //formirmao niz spotova kako bi se registrovao i ID nakon inserta
        $spotArrayAfterInsert = array();
        foreach ($sportArray as $spotValue) {
            //$spot = new Spot();
            $spot = $spotValue;
            $result1 = $dbBroker->insert($spot);
            $result = $result && $result1;
            $spotID = $dbBroker->getLastInsertedId();
            $spot->setSpotID($spotID);
            array_push($spotArrayAfterInsert, $spot);
        }

        $responseNew = new CoreAjaxResponseInfo();

        if ($result) {
            $dbBroker->commit();
            $dbBroker->close();
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
            return $responseNew; //Ovde treba videti sta treba da se vrati ukoliko se zahtev ne snimi kako treba
        }

        /******************* kraj UBACUJE U BAZU ZAHTEV I SPOTOVE ******************************/

        $object = new TmpKampanjaClass($kampanjaZahtev->getKlijentID(), $kampanjaZahtev->getAgencijaID(), $kampanjaZahtev->getRadioStanicaID(), $kampanjaZahtev->getBrendID(), $kampanjaZahtev->getNaziv(), $kampanjaZahtev->getDatumPocetka(), $kampanjaZahtev->getDatumKraja(), $kampanjaZahtev->getBudzet(), $kampanjaZahtev->getNapomena(), $spotArrayAfterInsert, $ponudaId, NULL, $kampanjaZahtev->getTipPlacanjaID());
        //$object2 = new TmpKampanjaClass($kampanjaZahtev->getKlijentID(), $kampanjaZahtev->getAgencijaID(), $kampanjaZahtev->getRadioStanicaID(), $kampanjaZahtev->getBrendID(), $kampanjaZahtev->getNaziv(), $kampanjaZahtev->getDatumPocetka(), $kampanjaZahtev->getDatumKraja(), $kampanjaZahtev->getBudzet(), $kampanjaZahtev->getNapomena(), $spotArrayAfterInsert, $ponudaId, NULL);

        //Ovde dodajemo podatke o spotovima u tmp kampanju
        //$object->setSpotArray($spotArrayAfterInsert);

        //$tmp = $object->getTmpKampanja();




        //$tmp = $object->getTmpKampanja(0);

        $object->getTmpKampanja();

        if($object->predjen_budzet){
            $object2 = new TmpKampanjaClass($kampanjaZahtev->getKlijentID(), $kampanjaZahtev->getAgencijaID(), $kampanjaZahtev->getRadioStanicaID(), $kampanjaZahtev->getBrendID(), $kampanjaZahtev->getNaziv(), $kampanjaZahtev->getDatumPocetka(), $kampanjaZahtev->getDatumKraja(), $kampanjaZahtev->getBudzet(), $kampanjaZahtev->getNapomena(), $spotArrayAfterInsert, $ponudaId, NULL, $kampanjaZahtev->getTipPlacanjaID());
            $object2->getTmpKampanja(1);//zanemaren budzet
        }else{
            $object2=false;
        }


        //$tmp2 = $object->getTmpKampanja(1);


        //$tmp2 = $object2->getTmpKampanja(1);//zanemaren budzet


        if (isset($_SESSION['tmpKampanja'])) {
            unset($_SESSION['tmpKampanja']);
        }
        $_SESSION['tmpKampanja'] = serialize($object);

        if (isset($_SESSION['tmpKampanja2'])) {
            unset($_SESSION['tmpKampanja2']);
        }


        if($object->predjen_budzet){
            $_SESSION['tmpKampanja2'] = serialize($object2);
        }
/*
        $tmp_arr=array(
            $tmp,
            $tmp2
        );*/



        $tmp_arr=array(
            $object,
            $object2
        );

        return $tmp_arr;
        //echo($tmp->zaNikolu());
    }

    public function KampanjaManualCreate(KampanjaZahtev $kampanjaZahtev) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kampanjaZahtev);
        $sportArray = $kampanjaZahtev->getSpotArray();
        //formirmao niz spotova kako bi se registrovao i ID nakon inserta
        $spotArrayAfterInsert = array();
        foreach ($sportArray as $spotValue) {
            $spot = new Spot();
            $spot = $spotValue;
            //Ovde registrujemo neke zakucane elememnte kak bi s epsolužili istim algoritmom za dobianej zauzetih blokova
            //Ovi zakucani parametri utiču na spot pa treb amožda smisliti foru kako da s eelminišu
            $spotUcestalost[1] = 0;
            $spotUcestalost[2] = 0;
            $spotUcestalost[3] = 0;
            $spotUcestalost[4] = 0;
            $spotUcestalost[5] = 0;
            $spot->setSpotUcestalost($spotUcestalost);
            $spot->setUcestalostSuma();
            //$spot->setSpotTrajanje(0);
            $spot->setPeriodi(array(1, 2, 3, 4, 5));
            $spot->setDani(array(1, 2, 3, 4, 5, 6, 7));
            $spot->setPremiumBlokovi(3);
            $result1 = $dbBroker->insert($spot);
            $result = $result && $result1;
            $spotID = $dbBroker->getLastInsertedId();
            $spot->setSpotID($spotID);
            array_push($spotArrayAfterInsert, $spot);
        }
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1) {
            $dbBroker->commit();
            $dbBroker->close();
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
            return $responseNew; //Ovde treba videti sta treba da se vrati ukoliko se zahtev ne snimi kako treba
        }
        $object = new TmpKampanjaClass($kampanjaZahtev->getKlijentID(), $kampanjaZahtev->getAgencijaID(), $kampanjaZahtev->getRadioStanicaID(), $kampanjaZahtev->getBrendID(), $kampanjaZahtev->getNaziv(), $kampanjaZahtev->getDatumPocetka(), $kampanjaZahtev->getDatumKraja(), $kampanjaZahtev->getBudzet(), $kampanjaZahtev->getNapomena(), $spotArrayAfterInsert, 0, NULL, $kampanjaZahtev->getTipPlacanjaID());
        //Ovde dodajemo podatke o spotovima u tmp kampanju
        //$object->setSpotArray($spotArrayAfterInsert);                                
        //$tmp = $object->getTmpKampanja();
        $object->getTmpKampanja();
        if (isset($_SESSION['tmpKampanja'])) {
            unset($_SESSION['tmpKampanja']);
        }

        $_SESSION['tmpKampanja'] = serialize($object);

        return $object;
        //echo($tmp->zaNikolu());
    }

    public function KampanjaInsert(Kampanja $kampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kampanja);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana kampanja");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function KampanjaUpdate(Kampanja $kampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->update($kampanja, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno promenjena kampanja");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija za brisanje 
    public function KampanjaDelete(Kampanja $kampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();

        $result3 = $this->KampanjaOnBeforeEventDelete($kampanja);

        $kampanjaSpot = new KampanjaSpot();
        $condition4 = " KampanjaID = " . $kampanja->getKampanjaID();
        $result4 = $dbBroker->delete($kampanjaSpot, $condition4);//nemoze da obrise ref integritet kampanjaspot

        $condition5 = " KampanjaID = " . $kampanja->getKampanjaID();
        $result5 = $dbBroker->delete($kampanja, $condition5);//nemoze da obrise ref integritet kampanjaspot

        /*
        send_respons_boban($result4);
        exit;*/


        $responseNew = new CoreAjaxResponseInfo();
        if ($result3 && $result4 && $result5 ) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno obrisana stavka");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }






    public function KampanjaStatusPromena(Kampanja $kampanja,$napomena) {



        global $korisnik_init;


        $dbBroker = new CoreDBBroker();


        /*
                        send_respons_boban($kampanja->getAllAttributes());
                        exit;*/





        $query = "SELECT * FROM kampanja WHERE KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->selectOneRow($query);
        $statusKampanjaID_old = $result['StatusKampanjaID'];


        /******************* za mejl *********************/

        $kampanjaID = 0+ $result['KampanjaID'];
        $nazivKampanja = $result['Naziv'];
        $RadioStanicaID = $result['RadioStanicaID'];
        $DatumPocetka = $result['DatumPocetka'];

/*        switch ($RadioStanicaID) {
            case 1:
                $RadioStanica = "s-juzni";
                break;
            case 2:
                $RadioStanica = "s-mix";
                break;
            default:
                die('radio not set!!!');
        }
*/
		$switchclass = new SwitchClass();
		$RadioStanica = $switchclass->GetSwitchRadio($radioStanicaID);
		unset($switchclass);

        /******************* za mejl *********************/



        if($statusKampanjaID_old==3){//otkazana
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Nemožete promeniti status, kampanja je već ranije otkazana !");
            $dbBroker->close();
            return $responseNew;
        }

        $dbBroker->beginTransaction();
        $statusKampanjaID = $kampanja->getStatusKampanjaID();





        if($statusKampanjaID!=2 and $statusKampanjaID!=3){//odbijena
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Kampanju možete samo potvrditi ili otkazati !");
            $dbBroker->close();
            return $responseNew;
        }


        if ($statusKampanjaID ==2 && $statusKampanjaID_old==2) {
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Kampanja je već ranije potvrđena !");
            $dbBroker->close();
            return $responseNew;
        }

        if ($statusKampanjaID == 2) {

            if ($korisnik_init['tipKorisnik'] == 3) {

                $dStart = new DateTime(date("Y-m-d"));
                $dEnd  = new DateTime($DatumPocetka);
                $dDiff = $dStart->diff($dEnd);

                if($dDiff->d<2 || $dDiff->invert==1) {
                    $responseNew = new CoreAjaxResponseInfo();
                    $responseNew->SetSuccess('false');
                    $responseNew->SetMessage("Nemožete potvrditi kampanja jer je ostalo manje od dva dana do početka kampanje. Za pomoć obratite se višim instancama!");
                    $dbBroker->close();
                    return $responseNew;
                }
            }
        }


        $condition = " KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->update($kampanja, $condition);

        //$query = "UPDATE kampanja SET StatusKampanjaID = " . $statusKampanjaID . " WHERE KampanjaID = " . $kampanja->getKampanjaID();

        //ukoliko je otkazana kampanaj počititi sve
        $result2=true;
        $mediaPlan="";
        if ($statusKampanjaID == 3) {


            if ($statusKampanjaID_old == 2) {

                if($korisnik_init['tipKorisnik']==3){
                    $responseNew = new CoreAjaxResponseInfo();
                    $responseNew->SetSuccess('false');
                    $responseNew->SetMessage("Nemože se otkazati potvrdjena kampanja !");
                    $dbBroker->close();
                    return $responseNew;
                }

            }



            $kampanjaPdfClass = new KampanjaPdfClass();
            $mediaPlan = $kampanjaPdfClass->MediaPlanForCampaigne($kampanja->getKampanjaID());

            $result2=$this->KampanjaOnBeforeEventDelete($kampanja);
        }









        $query = "SELECT PonudaID FROM ponuda WHERE KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->selectOneRow($query);
        $ponudaID = $result['PonudaID'];

        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();

        $ponudaIstorija->setPonudaID($ponudaID);
        $ponudaIstorija->setStatusPonudaID($statusKampanjaID);
        $ponudaIstorija->setNapomena($napomena);
        $ponudaIstorija->setMediaPlan($mediaPlan);

        $ponudaIstorija->setKorisnikID($_SESSION['sess_idkor']);

        $result3 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);








        //$result = $dbBroker->simpleQuery($query);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result2 && $result3) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno promenjen  status kampanje.");



            if ($statusKampanjaID == 2) {//mejl



                $query='
SELECT
    spot.SpotName,
    kampanjablok.BlokID,
    kampanjablok.Datum
    FROM
    kampanja
    INNER JOIN kampanjablok ON kampanja.KampanjaID = kampanjablok.KampanjaID
    INNER JOIN spot ON kampanjablok.SpotID = spot.SpotID
    WHERE
    kampanja.KampanjaID = '.$kampanjaID.'
    ORDER BY
    spot.SpotName ASC,
    kampanjablok.Datum ASC,
    kampanjablok.BlokID ASC
';

                $result = $dbBroker->selectManyRows($query);

                $spots_arr = array();
                $spot_name="";
                foreach ($result['rows'] as $row) {

                    if (!$spots_arr) {

                        $spot_name=$row['SpotName'];
                        $blok_id=$row['BlokID'];
                        $datum=$row['Datum'];

                        $spots_arr[]=array(
                            'spot_name'=>  $spot_name,
                            'blok_id'=>  $blok_id,
                            'datum'=>  $datum
                        );

                    }else{

                        if($spot_name != $row['SpotName']){
                            $spot_name=$row['SpotName'];
                            $blok_id=$row['BlokID'];
                            $datum=$row['Datum'];

                            $spots_arr[]=array(
                                'spot_name'=>  $spot_name,
                                'blok_id'=>  $blok_id,
                                'datum'=>  $datum
                            );
                        }

                    }

                }


                $query = "select BlokID, Sat from blok order by BlokID";

                $rows = $dbBroker->selectManyRows($query);

                $hours=array();
                foreach ($rows['rows'] as $row) {
                    $sat = $row['Sat'];
                    $blokId = $row['BlokID'];
                    $hours[$blokId]=$sat;
                }


                $korisnik_id= 0 + $_SESSION['sess_idkor'];
                $query = "SELECT * FROM korisnik WHERE KorisnikID = " . $korisnik_id;
                $result = $dbBroker->selectOneRow($query);
                $ime_i_prezime = $result['Ime']." ".$result['Prezime'];
                $email = $result['Email'];



                $text="Potvrdjena kampanja (".$nazivKampanja.") na radio stanici (".$RadioStanica.")<br/>";
                $text.="Potvrdio korisnik (".$ime_i_prezime.", ".$email.")<br/><br/>";
                $text.="Lista spotova za Kampanju:<br/>";
                $i=0;
                foreach ($spots_arr as $row) {
                    $i++;
                    $text.="Spot ".$i.": ".$row['spot_name']." (Početak emitovanja je ".date("d-m-Y",strtotime($row['datum']))." u ".$hours[$row['blok_id']]."h)<br/>";
                }



                $mails_arr=array();
                $mails_arr[]='b.djordjevic@smedia.rs';
                //$mails_arr[]='k.stankovic@smedia.rs';





                if ($korisnik_init['tipKorisnik'] == 3) {
                    //$mails_arr[]='a.vojin@as-media.rs';
                }




                foreach($mails_arr as $mail_addr) {
                    /*****************************EMAIL**********************************/
                    $to = $mail_addr;
                    $from = 'office@smedia.rs';

                    $subject = "Potvrdjena kampanja (" . $nazivKampanja . ")";

                    $message = $text;

                    $headers = array();
                    $headers[] = "MIME-Version: 1.0";
                    $headers[] = "Content-type: text/html; charset=UTF-8";
                    $headers[] = "From: Plan software <{$from}>";
                    //$headers[] = "Bcc: JJ Chong <bcc@domain2.com>";
                    $headers[] = "Reply-To: ".$ime_i_prezime." <".$email.">";
                    $headers[] = "Subject: {$subject}";
                    $headers[] = "X-Mailer: PHP/" . phpversion();

                    $flag = mail($to, $subject, $message, implode("\r\n", $headers));

                    if (!$flag) {
                        echo "error mail not sent";
                        exit;
                    }

                    /*****************************EMAIL**********************************/
                }


            }

        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }












    public function KampanjaEdit(Kampanja $kampanja) {





        $dbBroker = new CoreDBBroker();


        $query = "SELECT * FROM kampanja WHERE KampanjaID = ".$kampanja->getKampanjaID();
        $result = $dbBroker->selectOneRow($query);


        if($result){
            $popust_old=(int)$result['Popust'];
            $datumKraja_old=date("Y-m-d",strtotime($result['DatumKraja']));
            $cenaUkupno_old=$result['CenaUkupno'];
            $statusKampanjaID_old = $result['StatusKampanjaID'];

            $tipPlacanjaID_old = $result['TipPlacanjaID'];

        }else{
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
            return $responseNew;
        }

        

        if ($statusKampanjaID_old == 2) {

            global $korisnik_init;
            if($korisnik_init['tipKorisnik']==3){
                $responseNew = new CoreAjaxResponseInfo();
                $responseNew->SetSuccess('false');
                $responseNew->SetMessage("Nemože se editovati potvrdjena kampanja !");
                $dbBroker->close();
                return $responseNew;
            }

        }


        $popust=(int)$kampanja->getPopust();
        $datumKraja=$kampanja->getDatumKraja();
        $tipPlacanjaID=$kampanja->getTipPlacanjaID();


        //send_respons_boban($popust_old."-".$popust." xxx ".$datumKraja_old."-".$datumKraja." xxx ".$cenaUkupno_old);



        $TF1=false;
        if($popust!=$popust_old){
            $TF1=true;
        }
        $TF2=false;
        if($datumKraja!=$datumKraja_old){
            $TF2=true;
        }
        $TF3=false;
        if($tipPlacanjaID!=$tipPlacanjaID_old){
            $TF3=true;
        }


        if(($TF1 && $TF2) || ($TF1 && $TF3) || ($TF2 && $TF3)){
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Pri izmeni morate promeniti samo jedan parametar !!!");
            $dbBroker->close();
            return $responseNew;
        }



        if($TF1 || $TF2 || $TF3) {
        }else{
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Nije bilo promena!");
            $dbBroker->close();
            return $responseNew;
        }



        $dbBroker->beginTransaction();


        $result1=false;

        if($popust_old != $popust){//promena popusta

            $koef=(100/(100-$popust_old))*((100-$popust)/100);

            $query = "UPDATE kampanjablok SET  CenaEmitovanja=(CenaEmitovanja *".$koef.") WHERE KampanjaID = ".$kampanja->getKampanjaID();
            $result1 = $dbBroker->simpleQuery($query);
/*
            $query = "UPDATE kampanja SET  CenaUkupno=(CenaUkupno *".$koef.") WHERE KampanjaID = ".$kampanja->getKampanjaID();
            $result2 = $dbBroker->simpleQuery($query);
*/

            $kampanja->setCenaUkupno($cenaUkupno_old*$koef);

        }


        if($datumKraja_old != $datumKraja){//promena datuma

            if($datumKraja<$datumKraja_old){



                $datumKraja=date('Y-m-d', strtotime($datumKraja));
                $now = date('Y-m-d', time());
                if (strtotime($datumKraja) < strtotime($now)) {
                    $responseNew = new CoreAjaxResponseInfo();
                    $responseNew->SetSuccess('false');
                    $responseNew->SetMessage('Datum kraja nemože biti datum koji je prošao!');
                    $dbBroker->close();
                    return $responseNew;
                }



                $result1 = $this->KampanjaOnBeforeEventDelete($kampanja,$datumKraja);

/*
                $kampanjaBlok = new KampanjaBlok();
                $condition = " KampanjaID = " . $kampanja->getKampanjaID() . " AND Datum > '$datumKraja'";
                $result2 = $dbBroker->delete($kampanjaBlok, $condition);*/

                $query = "SELECT sum(CenaEmitovanja) as suma from kampanjablok WHERE KampanjaID = ".$kampanja->getKampanjaID();
                $result8 = $dbBroker->selectOneRow($query);
                $cena_ukupno=0+$result8['suma'];
                //send_respons_boban("jjj".$cena_ukupno);

                $kampanja->setCenaUkupno($cena_ukupno);


            }else{
                $responseNew = new CoreAjaxResponseInfo();
                $dbBroker->rollback();
                $responseNew->SetSuccess('false');
                $responseNew->SetMessage("Datum završetka mora biti manji od trenutnog !");
                $dbBroker->close();
                return $responseNew;
            }


        }



        if($tipPlacanjaID != $tipPlacanjaID_old){//promena popusta

            $koef=1;

            if($tipPlacanjaID==8){
                $koef=2;
            }
            if($tipPlacanjaID_old==8){
                $koef=1/2;
            }



            $query = "UPDATE kampanjablok SET  CenaEmitovanja=(CenaEmitovanja *".$koef.") WHERE KampanjaID = ".$kampanja->getKampanjaID();
            $result1 = $dbBroker->simpleQuery($query);
            /*
                        $query = "UPDATE kampanja SET  CenaUkupno=(CenaUkupno *".$koef.") WHERE KampanjaID = ".$kampanja->getKampanjaID();
                        $result2 = $dbBroker->simpleQuery($query);
            */

            $kampanja->setCenaUkupno($cenaUkupno_old*$koef);

        }












        $condition = " KampanjaID = " .$kampanja->getKampanjaID();
        $result2 = $dbBroker->update($kampanja, $condition);



/*
        //$kampanjaPdfClass = new KampanjaPdfClass();
        $mediaPlan = "";//$kampanjaPdfClass->MediaPlanForCampaigne($kampanja->getKampanjaID());

        $query = "SELECT PonudaID FROM ponuda WHERE KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->selectOneRow($query);
        $ponudaID = $result['PonudaID'];

        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();

        $ponudaIstorija->setPonudaID($ponudaID);
        $ponudaIstorija->setStatusPonudaID($kampanja->getStatusKampanjaID());
        $ponudaIstorija->setNapomena("Izmenjeni parametri kampanje !");
        $ponudaIstorija->setMediaPlan($mediaPlan);

        $ponudaIstorija->setKorisnikID($_SESSION['sess_idkor']);

        $result4 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);
*/








        $responseNew = new CoreAjaxResponseInfo();
        if ($result1 && $result2 ) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno promenjeni parametri kampanje !");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
        // odraditi dalji update finansijskog statusa kampanje
    }




















//    public function KampanjaPromeniFinansijskiStatus(Kampanja $kampanja) {
//        $dbBroker = new CoreDBBroker();
//        $dbBroker->beginTransaction();
//        $condition = " KampanjaID = ".$kampanja->getKampanjaID();
//        $result = $dbBroker->update($kampanja, $condition);
//        $query = "UPDATE kampanja SET FinansijskiStatusID = ".$kampanja->getFinansijskiStatusID()." WHERE KampanjaID = ".$kampanja->getKampanjaID();
//        $result = $dbBroker->simpleQuery($query);
//        $responseNew = new CoreAjaxResponseInfo();
//        if ($result) {
//            $dbBroker->commit();
//            $responseNew->SetSuccess('true');
//            $responseNew->SetMessage("Uspešno promenjen finansijski status kampanje.");
//        }
//        else {
//            $dbBroker->rollback();
//            $responseNew->SetSuccess('false');
//            $responseNew->SetMessage(CoreError::getError());
//        }
//        $dbBroker->close();
//        return $responseNew;
//        // odraditi dalji update finansijskog statusa kampanje
//    }










    //Funkcija za punjenje kampanje
    public function KampanjaGetList(FilterKampanja $filter) {
        if ($filter->klijentListFilter <> '') {
            $queryKlijentListFilter = "and (K.KlijentID in (" . $filter->klijentListFilter . "))";
        } else {
            $queryKlijentListFilter = "";
        }
        if ($filter->agencijaListFilter <> '') {
            $queryAgencijaListFilter = "and (K.AgencijaID in (" . $filter->agencijaListFilter . "))";
        } else {
            $queryAgencijaListFilter = "";
        }
        $query = "select 
                    K.KampanjaID,
                    K.RadioStanicaID,
                    R.Naziv as RadioStanica,
                    K.Naziv as Naziv,
                    KL.Naziv as Klijent,
                    DATE_FORMAT(K.DatumPocetka,'%d.%m.%Y') as DatumPocetka,
                    DATE_FORMAT(K.DatumKraja,'%d.%m.%Y') as DatumKraja,
                    F.Naziv as FinansijskiStatus,
                    A.Naziv as Agencija,
                    concat_ws(' ', K1.Ime, K1.Prezime) as KorisnikUneo,
                    K.PrilogIzjava,
                    K.UkupnoSekundi as SpotTrajanje,
                    K.GratisSekunde,
                    K.Popust,
                    K.CenaUkupno,
                    K.CenaKonacno,
                    B.Naziv as Brend,
                    KN.Naziv as NacinPlacanja,
                    S.Naziv as Status,
                    K.VremeZaPotvrdu,
                    K.VremePostavke,
                    K.VremePotvrde,
                    concat_ws(' ', K2.Ime, K2.Prezime) as KorisnikPotvrda,
                    K.TipPlacanjaID
                    from kampanja as K
                    left outer join finansijskistatuskampanja as F on K.FinansijskiStatusID = F.FinansijskiStatusKampanjaID
                    left outer join korisnik as K1 on K.KorisnikID = K1.KorisnikID
                    left outer join radiostanica R on K.RadioStanicaID = R.RadioStanicaID
                    left outer join agencija as A on K.AgencijaID = A.AgencijaID
                    left outer join klijent as KL on K.KlijentID = KL.KlijentID
                    left outer join brend as B on K.BrendID = B.BrendID
                    left outer join korisnik as K2 on K.KorisnikPotvrdaID = K2.KorisnikID
                    left outer join kampanjanacinplacanja as KN on K.KampanjaNacinPlacanjaID = KN.KampanjaNacinPlacanjaID
                    left outer join statuskampanja as S on K.StatusKampanjaID = S.StatusKampanjaID
                where ('$filter->kampanjaNazivFilter' = '' or (K.Naziv like '%$filter->kampanjaNazivFilter%'))
                and ('$filter->datumPocetkaFilter' = '' or (K.DatumPocetka >= '$filter->datumPocetkaFilter'))   
                and ('$filter->datumKrajaFilter' = '' or (K.DatumPocetka <= '$filter->datumKrajaFilter'))  
                and ($filter->statusFilterID is null or (K.StatusKampanjaID = $filter->statusFilterID))  
                and ($filter->klijentID is null or (K.KlijentID = $filter->klijentID))
                and ($filter->nacinPlacanjaFilterID is null or (K.KampanjaNacinPlacanjaID = $filter->nacinPlacanjaFilterID))" . $queryKlijentListFilter . $queryAgencijaListFilter;


/*
        .
        "and ($filter->tipKorisnika is null  or $filter->tipKorisnika = " . TipKorisnika::InterniKorisnik . "
                or ($filter->tipKorisnika = " . TipKorisnika::KlijentskiKorisnik . " and (K.KlijentID = (select KlijentID from korisnik where KorisnikID = '$filter->korisnikID')))
                or ($filter->tipKorisnika = " . TipKorisnika::AgencijskiKorisnik . " and (K.AgencijaID = (select AgencijaID from korisnik where KorisnikID = '$filter->korisnikID'))))"
        */



        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.VremePostavke desc ";
        }
        $query .= $querySort;
        //file_put_contents("neki2014042301.txt", $query);
        //$query .= "LIMIT $filter->start, $filter->limit";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function KampanjaPonudaGetList(FilterKampanjaPonuda $filter) {
        $query = "select 
                    K.KampanjaID,
                    K.Naziv as Naziv,
                    KL.Naziv as Klijent,
                    DATE_FORMAT(K.DatumPocetka,'%d.%m.%Y') as DatumPocetka,
                    DATE_FORMAT(K.DatumKraja,'%d.%m.%Y') as DatumKraja,
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
                    K.VremeZaPotvrdu,
                    K.VremePostavke,
                    K.VremePotvrde,
                    concat_ws(' ', K2.Ime, K2.Prezime) as KorisnikPotvrda
                    from kampanja as K
                    left outer join finansijskistatuskampanja as F on K.FinansijskiStatusID = F.FinansijskiStatusKampanjaID
                    left outer join korisnik as K1 on K.KorisnikID = K1.KorisnikID
                    left outer join klijent as KL on K.KlijentID = KL.KlijentID
                    left outer join agencija as A on K.AgencijaID = A.AgencijaID
                    left outer join delatnost as D on K.DelatnostID = D.DelatnostID
                    left outer join korisnik as K2 on K.KorisnikPotvrdaID = K2.KorisnikID
                    left outer join kampanjanacinplacanja as KN on K.KampanjaNacinPlacanjaID = KN.KampanjaNacinPlacanjaID
                    left outer join statuskampanja as S on K.StatusKampanjaID = S.StatusKampanjaID
                    where ($filter->ponudaID is null or (K.PonudaID = $filter->ponudaID)) ";
        if ($sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.VremePostavke desc ";
        }
        $query .= $querySort;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function KampanjaPotvrdi(TmpKampanjaClass $tmpKampanja) {
        $dbBroker = new CoreDBBroker();

        $ponudaClass = new PonudaClass();
        $ponuda = new Ponuda();


        $dbBroker->beginTransaction();
        $responseNew = new CoreAjaxResponseInfo();

        $result = $this->ZauzmiBlokove($tmpKampanja, $dbBroker);//proverava u blokzauzece (zauzeta prva, zauzeta druga, preostalo sekundi)

        $result1 = $this->UnesiKampanju($tmpKampanja, $dbBroker);

        $result2 = $this->SacuvajZauzeteBlokove($tmpKampanja, $dbBroker);


        //$ponuda = new Ponuda();
        $ponuda->setPonudaID(-1);
        $ponuda->setKlijentID($tmpKampanja->getKlijentID());
        $ponuda->setKorisnikID($_SESSION['sess_idkor']);
        $ponuda->setSadrzaj($tmpKampanja->getNapomena());
        $ponuda->setVrednost($tmpKampanja->getCampaignePrice());

        $ponuda->setKampanjaID($tmpKampanja->getKampanjaID());

        $ponuda->setStatusPonudaID(1);
        $ponuda->setVremePostavke(date("Y-m-d H:i:s"));


        $result3 = $ponudaClass->PonudaInsert($ponuda, $dbBroker);

        /*
        $ponudaLastId = $dbBroker->getLastInsertedId();
        $result_ponudaIstorija = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);
        */


        if ($result && $result1 && $result2 && $result3) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana kampanja");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function SacuvajZauzeteBlokove(TmpKampanjaClass $tmpKampanja, $dbBroker) {
        $error = 0;
        $tempSpotArray = $tmpKampanja->getTempSpotArray();
        //$izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
        $kampanjaLastId = $tmpKampanja->getKampanjaID();
        if (isset($kampanjaLastId) && $kampanjaLastId > 0) {
            foreach ($tempSpotArray as $tempSpot) {
                $izabraniBlokovi = $tempSpot['izabraniBlokovi'];
                foreach ($izabraniBlokovi as $dan => $niz) {
                    foreach ($niz as $izabraniBlok => $cena) {

                        //$blokPozicija = array();
                        $blokPozicija = explode('/', $izabraniBlok);

                        $query = "SELECT * FROM kampanjablok WHERE Datum = '" . $dan . "' AND BlokID = " . $blokPozicija[0] . " AND RadioStanicaID = " . $tmpKampanja->getRadioStanicaID() . " AND Redosled = ".$blokPozicija[1];
                        $result = $dbBroker->selectOneRow($query);
                        if($result){
                            $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo poziciju u bloku koju ste Vi želeli, molimo Vas da ponovo kreirate kampanju.';//.$query;
                            $error = 1;
                            break(3);
                        }

                        $query = "SELECT * FROM kampanjablok WHERE Datum = '" . $dan . "' AND BlokID = " . $blokPozicija[0] . " AND RadioStanicaID = " . $tmpKampanja->getRadioStanicaID() . " AND Redosled IN (".($blokPozicija[1]-1) .",".($blokPozicija[1]+1) .") AND (GlasID = ".$tempSpot['glasID'] ." OR DelatnostID = ".$tmpKampanja->getDelatnostID().")";
                        $result = $dbBroker->selectOneRow($query);
                        if($result){
                            $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo susedni spot u bloku sa istim glasom ili delatnošću, molimo Vas da ponovo kreirate kampanju.';//.$query;
                            $error = 1;
                            break(3);
                        }

                        $kampanjaBlok = new KampanjaBlok();

                        $kampanjaBlok->setRadioStanicaID($tmpKampanja->getRadioStanicaID());
                        $kampanjaBlok->setKampanjaID($kampanjaLastId);
                        $kampanjaBlok->setSpotID($tempSpot['spotID']);
                        $kampanjaBlok->setBlokID($blokPozicija[0]);
                        $kampanjaBlok->setDatum($dan);
                        $kampanjaBlok->setRedosled($blokPozicija[1]);
                        $kampanjaBlok->setGlasID($tempSpot['glasID']);
                        $kampanjaBlok->setDelatnostID($tmpKampanja->getDelatnostID());
                        $kampanjaBlok->setCenaEmitovanja($cena);

                        $result = $dbBroker->insert($kampanjaBlok);

                        if (!$result) {
                            $error = 1;
                        }

                        unset($kampanjaBlok);
                    }
                }
            }
        } else {
            $error = 1;
        }

        if ($error == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function ZauzmiBlokove(TmpKampanjaClass $tmpKampanja, $dbBroker) {
        $error = 0;
        //$izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
        $tempSpotArray = $tmpKampanja->getTempSpotArray();
        foreach ($tempSpotArray as $tempSpot) {
            $izabraniBlokovi = $tempSpot['izabraniBlokovi'];
            foreach ($izabraniBlokovi as $dan => $niz) {
                foreach ($niz as $blok => $cena) {
                    $blokPozicija = array();
                    $blokPozicija = explode('/', $blok);
                    $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $blokPozicija[0] . " AND RadioStanicaID = " . $tmpKampanja->getRadioStanicaID();
                    $result = $dbBroker->selectOneRow($query);

                    if ($blokPozicija[1] == 1 && $result['ZauzetaPrva'] == 1) {
                        $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi želeli, molimo Vas da ponovo kreirate kampanju.';
                        $error = 1;
                    } elseif ($blokPozicija[1] == 2 && $result['ZauzetaDruga'] == 1) {
                        $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi želeli, molimo Vas da ponovo kreirate kampanju.';
                        $error = 1;
                    } elseif ($result['PreostaloSekundi'] < $tempSpot['spotTrajanje']) {
                        $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi želeli, molimo Vas da ponovo kreirate kampanju.';
                        $error = 1;
                    } else {
                        $blokZauzece = new BlokZauzece();
                        $blokZauzece->setGlasIDs($result['GlasIDs'] . $tempSpot['glasID'] . ',');
                        if ($tmpKampanja->getDelatnostID() > 0) {
                            $blokZauzece->setDelatnostIDs($result['DelatnostIDs'] . $tmpKampanja->getDelatnostID() . ',');
                        }
                        $blokZauzece->setBlokZauzeceID($result['BlokZauzeceID']);
                        $blokZauzece->setZauzetoSekundi($result['ZauzetoSekundi'] + $tempSpot['spotTrajanje']);
                        $blokZauzece->setPreostaloSekundi($result['PreostaloSekundi'] - $tempSpot['spotTrajanje']);
                        $blokZauzece->setNepotvrdjenoSekundi($result['NepotvrdjenoSekundi'] + $tempSpot['spotTrajanje']);
                        if ($blokPozicija[1] == 1) {
                            $blokZauzece->setZauzetaPrva(1);
                        } elseif ($blokPozicija[1] == 2) {
                            $blokZauzece->setZauzetaDruga(1);
                        } else {
                            
                        }

                        $condition = " BlokZauzeceID = " . $blokZauzece->getBlokZauzeceID() . " AND RadioStanicaID = " . $tmpKampanja->getRadioStanicaID();

                        $result1 = $dbBroker->update($blokZauzece, $condition);
                        if ($result === false || $result1 === false) {
                            $error = 1;
                        }
                        unset($blokZauzece);
                    }
                }
            }
        }
        if ($error == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function UnesiKampanju(TmpKampanjaClass $tmpKampanja, $dbBroker) {
        $query = "SELECT * FROM kampanjazahtev WHERE KampanjaZahtevID = (SELECT MAX(KampanjaZahtevID) FROM kampanjazahtev WHERE KorisnikID = " . $_SESSION['sess_idkor'] . ")";
        $result = $dbBroker->selectOneRow($query);

        $newKampanja = new Kampanja();
        //Ovo moramo da promenimo i sad cmeo za datum pocetka i datum kraja da stavimo na nivou cele kmpanje a ne na nivou pojedinacnuih spotova
        //$nizDatuma = array_keys($tmpKampanja->getIzabraniBlokoviZaPrikaz());

        $periodZaPotvrdu = 2; // u danima
        $vremeZaPotvrdu = date('Y-m-d H:i:s', strtotime($result['VremePostavke'] . '+' . $periodZaPotvrdu . ' days'));

        $newKampanja->setKlijentID($result['KlijentID']);
        $newKampanja->setNaziv($tmpKampanja->getNaziv());
        $newKampanja->setDatumPocetka($tmpKampanja->getStartOfCampaigne());
        $newKampanja->setDatumKraja($tmpKampanja->getEndOfCampaigne());
        //$newKampanja->setUcestalost($tmpKampanja->getUcestalost());
        $newKampanja->setAgencijaID($result['AgencijaID']);
        $newKampanja->setKorisnikID($result['KorisnikID']);
        //$newKampanja->setRedosledUBloku($_POST['redosledUBloku']);
        $newKampanja->setFinansijskiStatusID(FinansijskiStatus::Nefakturisana);
        $newKampanja->setStatusKampanjaID(StatusKampanjaEnum::Rezervisana);
        $newKampanja->setBudzet($tmpKampanja->getBudzet());
        $newKampanja->setVremePostavke($result['VremePostavke']);
        $newKampanja->setVremeZaPotvrdu($vremeZaPotvrdu);
        //$newKampanja->setSpotUkupno($tmpKampanja->getTrajanjeSpota());
        //$newKampanja->setSpotUkupno($tmpKampanja->getTrajanjeSpota());
        $newKampanja->setRadioStanicaID($result['RadioStanicaID']);
        $newKampanja->setDelatnostID($tmpKampanja->getDelatnostID());
        $newKampanja->setBrendID($tmpKampanja->getBrendID());
        $newKampanja->setCenaUkupno($tmpKampanja->getCampaignePrice());
        $newKampanja->setPonudaID($tmpKampanja->getPonudaID());
        $newKampanja->setSablonID($tmpKampanja->getSablonID());




        $newKampanja->setPopust($tmpKampanja->getPopust());

        $newKampanja->setTipPlacanjaID($tmpKampanja->getTipPlacanjaID());

        $result1 = $dbBroker->insert($newKampanja);
        //Deo gde ćemo vezati spot za konačnu kampanju
        $kampanjaID = $dbBroker->getLastInsertedId();
        $tmpKampanja->setKampanjaID($kampanjaID);
        $spotArray = $tmpKampanja->getSpotArray();
        foreach ($spotArray as $spot) {
            //$spot instanceof  Spot;
            $spotID = $spot->getSpotID();
            $spotKampanja = new KampanjaSpot();
            $spotKampanja->setKampanjaID($kampanjaID);
            $spotKampanja->setSpotID($spotID);
            $result2 = $dbBroker->insert($spotKampanja);
            $result1 = $result1 && $result2;
        }
        if ($result1) {
            return true;
        } else {
            return false;
        }
    }

    public function KampanjaPregledEmitovanja(Kampanja $kampanja) {


        $daysForCampaigne=$this->getDaysForCampaigne($kampanja->getKampanjaID());


        $dani = array();

        foreach($daysForCampaigne as $value){
            $dani[$value]=array();
        }
//send_respons_boban($dani);


        $dbBroker = new CoreDBBroker();




        $campaigneDetails=$this->getCampaigneDetails($kampanja->getKampanjaID());


        $cenaKampanje = $campaigneDetails['CenaUkupno'];
        $sablonId = 0+$campaigneDetails['SablonID'];
        $popust = 0+$campaigneDetails['Popust'];


/*
        public function getCampaigneDetails($campaigneID) {
            $query = "SELECT kl.KlijentID, k.AgencijaID, k.SablonID, kl.DelatnostID, k.RadioStanicaID, k.CenaUkupno, k.Popust, k.Naziv
            from kampanja k
            inner join klijent kl on k.KlijentID = kl.KlijentID
            WHERE k.KampanjaID =" . $campaigneID;
            $dbBroker = new CoreDBBroker();
            $result = $dbBroker->selectOneRow($query);
            return $result;
        }*/




















        $query = "SELECT * FROM kampanjaspot
                    WHERE KampanjaID = " . $kampanja->getKampanjaID();
        $result = $dbBroker->selectManyRows($query);
        $spotBroj=count($result['rows']);






        $query = "SELECT kampanjablok.BlokID, 
                    kampanjablok.Datum, 
                    kampanjablok.Redosled, 
                    kampanja.KampanjaID,
                    spot.SpotTrajanje, 
                    spot.SpotName, 
                    spot.SpotID, 
                    kampanja.KampanjaID, 
                    kampanja.CenaUkupno, 
                    kampanja.Naziv,
                    kampanja.SablonID,
                    kampanja.Popust,
                    blok.VremeStart, 
                    blok.VremeEnd, 
                    blok.Trajanje
                    FROM kampanjablok
                    INNER JOIN kampanja
                    ON kampanjablok.KampanjaID = kampanja.KampanjaID
                    INNER JOIN blok 
                    ON kampanjablok.BlokID = blok.BlokID
                    left outer join spot on spot.SpotID = kampanjablok.SpotID
                    WHERE kampanjablok.KampanjaID = " . $kampanja->getKampanjaID() . "
                    ORDER BY kampanjablok.Datum, spot.SpotID, kampanjablok.BlokID";
        $result = $dbBroker->selectManyRows($query);
        //$cenaKampanje = 0;

        foreach ($result['rows'] as $row) {
            /*$cenaKampanje = $row['CenaUkupno'];
            $sablonId = $row['SablonID'];
            $popust = $row['Popust'];*/


            $dani[$row['Datum']][] = array('Title' => $row['Naziv'], 'CampaigneID' => $row['KampanjaID'], 'SpotName' => $row['SpotName'], 'SpotID' => $row['SpotID'], 'StartDate' => '2012-01-01 ' . $row['VremeStart'], 'EndDate' => '2012-01-01 ' . $row['VremeEnd'], 'Duration' => $row['SpotTrajanje'], 'CommercialBlockOrderID' => $row['Redosled'], 'BlokId' => $row['BlokID'], 'DatumBloka' => $row['Datum']);
        }

        $i = 1;
        $j = 1;

        //$query1 = "select kampanjaspot.SpotID from kampanjaspot  where kampanjaspot.KampanjaID = " . $kampanja->getKampanjaID();

        $query1 = "
        SELECT
        kampanjaspot.SpotID,
        spot.SpotName
        FROM
        kampanjaspot
        LEFT JOIN spot ON kampanjaspot.SpotID = spot.SpotID
        WHERE
        kampanjaspot.KampanjaID =" . $kampanja->getKampanjaID();



        $result1 = $dbBroker->selectManyRows($query1);
        $spotIDs = array();
        $spotNames="";
        $k = 1;
        foreach ($result1['rows'] as $row) {
            $spotIDs[$k] = $row['SpotID'];
            $spotNames[$k] = $row['SpotName'];
            $k++;
        }

        $response = new stdClass();
        $schedulerDates = array();
        $schedulerCommercial = array();

        foreach ($dani as $dan => $vrednosti) {


            //$k = 0;
            $k=array();//niz


            //Promenljiva koja čuva sve blokove koji imaju neku informaciju
            $blockSelcted = array();
            foreach ($vrednosti as $vrednost) {
                $row2['Id'] = $j;
                $row2['Title'] = $vrednost['Title'];
                $row2['StartDate'] = $vrednost['StartDate'];
                $row2['EndDate'] = $vrednost['EndDate'];
                $row2['ResourceId'] = $i;
                $row2['Duration'] = $vrednost['Duration'];
                $row2['OtherClient'] = false;
                $row2['CommercialBlockOrderID'] = $vrednost['CommercialBlockOrderID'];
                $row2['BlokId'] = $vrednost['BlokId'];
                $row2['DatumBloka'] = $vrednost['DatumBloka'];
                $row2['CampaigneID'] = $vrednost['CampaigneID'];

                $row2['RadioStanicaID'] = $kampanja->getRadioStanicaID();

                $row2['Color'] = array_search($vrednost['SpotID'], $spotIDs);
                $row2['SpotName'] = $vrednost['SpotName'];
                $row2['SpotID'] = $vrednost['SpotID'];

                $schedulerCommercial[] = $row2;
                //$schedulerCommercial .= '{"Id":' . $j . ',"Title":"' . $vrednost['Title'] . '","StartDate":"' . $vrednost['StartDate'] . '","EndDate":"' . $vrednost['EndDate'] . '","ResourceId":' . $i . ',"Duration":"' . $vrednost['Duration'] . '","OtherClient":false, "CommercialBlockOrderID":"' . $vrednost['CommercialBlockOrderID'] . '"},';
                $j++;

                //$k++;
                $rb_spota_pom=array_search($vrednost['SpotID'], $spotIDs);
                if(!isset($k[$rb_spota_pom])){
                    $k[$rb_spota_pom]=0;
                }
                $k[$rb_spota_pom]++;


                array_push($blockSelcted, $vrednost['BlokId']);




            }
            //Ovd etreba da dodamo i za svaki beli blok da s epošalju osnovne informaciej kako bi mogli da radimo dodavnej na njemu
            //Popunićemo sve one blokIDeve koji s ene pojavljuju 
            $query1 = "SELECT * from blok";
            $result1 = $dbBroker->selectManyRows($query1);
            foreach ($result1['rows'] as $row) {
                if (!in_array($row['BlokID'], $blockSelcted)) {
                    $row3['Id'] = $j;
                    $row3['ResourceId'] = $i;
                    $row3['OtherClient'] = false;
                    $row3['BlokId'] = $row['BlokID'];
                    $row3['DatumBloka'] = $dan;
                    $row3['Color'] = 0;


                    if($row['BlokID']%2==0){
                        $row3['Color'] = 9;
                    }


                    $row3['StartDate'] = '2012-01-01 ' . $row['VremeStart'];
                    $row3['EndDate'] = '2012-01-01 ' . $row['VremeEnd'];
                    $row3['CampaigneID'] = $kampanja->getKampanjaID();

                    $row3['RadioStanicaID'] = $kampanja->getRadioStanicaID();

                    $schedulerCommercial[] = $row3;
                    $j++;
                }
            }
            $row1['Id'] = $i;
            $row1['Name'] = $dan;


            //$row1['Frequency'] = $k;
            $row1['Frequency']="";
            foreach ($k as $rb_spota_pom => $count_spota_pom) {
                $row1['Frequency'] .= $count_spota_pom." - ".$spotNames[$rb_spota_pom]."<br/>";
            }


            $schedulerDates[] = $row1;
            $i++;


        }



        //$capmaignePrice = '"capmaignePrice":' . $cenaKampanje;
        //$data = 'data:{' . $capmaignePrice . ',' . $schedulerDates . ',' . $schedulerCommercial . '}';
        //$responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $response->success = true;
            $response->data->campaigneID = $kampanja->getKampanjaID();
            $response->data->capmaignePrice = $cenaKampanje;
            $response->data->popust = $popust;

            $response->data->spotBroj = $spotBroj;


            $response->data->sablonId = 0;
            if(isset($sablonId)) {
                $response->data->sablonId = $sablonId;
            }

            $response->data->schedulerDates = $schedulerDates;
            $response->data->schedulerCommercial = $schedulerCommercial;
            //$data = '{success:true ,'.$evts.'}';
            //return $data;
        } else {
            $response->success = false;
            $response->msg = CoreError::getError();
            //$data = '{success:false , msg:'.CoreError::getError().'}';
            //return $data;
        }
        $dbBroker->close();
        return json_encode($response);
    }




    public function KampanjaGetListForBlock(FilterMediaPlanDetails $filter) {


        $query = "select distinct 
                    KB.Redosled,
                    K.Naziv as Naziv,
                    KL.Naziv as Klijent, 
                    DATE_FORMAT(K.DatumPocetka,'%d.%m.%Y') as DatumPocetka,
                    DATE_FORMAT(K.DatumKraja,'%d.%m.%Y') as DatumKraja,
                    S.SpotTrajanje as SpotTrajanje,
                    S.SpotName,
                    S.SpotLink,
                    G.ImePrezime as Glas,
                    B.Naziv as Brend
                    from kampanjablok KB
                    left outer join kampanja K on KB.KampanjaID = K.KampanjaID
                    left outer join klijent KL on K.KlijentID = KL.KlijentID
                    left outer join spot S on KB.SpotID = S.SpotID 
                    left outer join glas G on S.GlasID = G.GlasID
                    left outer join brend B on K.BrendID = B.BrendID
                where ($filter->blok = '' or (KB.BlokID = $filter->blok))
                and ('$filter->datum' = '' or (KB.Datum = '$filter->datum'))
                and ('$filter->radioStanicaID' = 'null' or (K.RadioStanicaID = '$filter->radioStanicaID'))";




        //and ('$filter->radioStanicaID' = 'null' or (K.RadioStanicaID = 2))";//$filter->radioStanicaID


                //and ('$filter->kampanjaID' = 'null' or (K.RadioStanicaID= (select k1.RadioStanicaID from kampanja k1 where KampanjaID = '$filter->kampanjaID')))";
        if ($sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by KB.Redosled asc ";
        }
        $query .= $querySort;
        //file_put_contents("neki.txt", $query);
        //$query .= "LIMIT $filter->start, $filter->limit";



        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);




        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function KampanjaValidateDND($startBlockId, $dragStartDate, $dragEndDate, $endBlockId, $dropStartDate, $dropStartDate, $dropEndDate) {
        $responseNew = new CoreAjaxResponseInfo();
        $responseNew->SetSuccess('true');
        return $responseNew;
    }

    public function KampanjaOnBeforeEventDelete(Kampanja $kampanja,$datumKraja=NULL) {
        $dbBroker = new CoreDBBroker();

        if(isset($datumKraja)){

            $query = "SELECT kb.SpotID, kb.BlokID, kb.Datum, kb.Redosled
            FROM kampanjablok as kb
            WHERE kb.KampanjaID = " . $kampanja->getKampanjaID() . " AND Datum > '$datumKraja'
            ORDER BY kb.SpotID, kb.Datum";

        }else {

            $query = "SELECT kb.SpotID, kb.BlokID, kb.Datum, kb.Redosled
            FROM kampanjablok as kb
            WHERE kb.KampanjaID = " . $kampanja->getKampanjaID() . "
            ORDER BY kb.SpotID, kb.Datum";

        }




        $result = $dbBroker->selectManyRows($query);

        $query1 = "SELECT s.SpotID, s.SpotTrajanje, s.GlasID
            FROM kampanjaspot as ks
            inner join spot s on ks.SpotID = s.SpotID
            WHERE ks.KampanjaID = " . $kampanja->getKampanjaID();
        $result1 = $dbBroker->selectManyRows($query1);

        $query2 = "SELECT k.RadioStanicaID, kl.DelatnostID
            from kampanja k 
            inner join klijent kl on k.KlijentID = kl.KlijentID
            WHERE k.KampanjaID = " . $kampanja->getKampanjaID();
        $result2 = $dbBroker->selectOneRow($query2);

        $spotDurationArray = array();
        $spotVoiceArray = array();
        foreach ($result1['rows'] as $row1) {
            $spotID = $row1['SpotID'];
            $spotDuration = $row1['SpotTrajanje'];
            $glasID = $row1['GlasID'];
            $spotDurationArray[$spotID] = $spotDuration;
            $spotVoiceArray[$spotID] = $glasID;
        }

        $result3 = true;
        foreach ($result['rows'] as $row) {

            $spotID = $row['SpotID'];
            $spotDuration = $spotDurationArray[$spotID];
            $blokID = $row['BlokID'];
            $datum = $row['Datum'];
            $redosled = $row['Redosled'];

            $query5 = "select * from blokzauzece where  BlokID = " . $blokID . " AND Datum = '" . $datum . "' AND RadioStanicaID = " . $result2['RadioStanicaID'];
            $result5 = $dbBroker->selectOneRow($query5);

            $glasIDs = $result5['GlasIDs'];
            $delatnostIDs = $result5['DelatnostIDs'];

            $blokZauzece = new BlokZauzece();
            $blokZauzece->setGlasIDs(str_replace($spotVoiceArray[$spotID] . ',', "", $glasIDs));
            $blokZauzece->setDelatnostIDs(str_replace($result2['DelatnostID'] . ',', "", $delatnostIDs));
            $blokZauzece->setZauzetoSekundi($result5['ZauzetoSekundi'] - $spotDuration);
            $blokZauzece->setPreostaloSekundi($result5['PreostaloSekundi'] + $spotDuration);
            $blokZauzece->setNepotvrdjenoSekundi($result5['NepotvrdjenoSekundi'] - $spotDuration);
            if ($redosled == 1) {
                $blokZauzece->setZauzetaPrva(0);
            } elseif ($redosled == 2) {
                $blokZauzece->setZauzetaDruga(0);
            } else {
                
            }

            $condition = " BlokZauzeceID = " . $result5['BlokZauzeceID'];

            $result3 = $result3 && ($dbBroker->update($blokZauzece, $condition));
        }

        //Obrisati sve iz kampanjeblok sto se tice te kampanje
        $kampanjaBlok = new KampanjaBlok();


        if(isset($datumKraja)) {
            $condition4 = " KampanjaID = " . $kampanja->getKampanjaID() ." AND Datum > '$datumKraja' ";
        }else{
            $condition4 = " KampanjaID = " . $kampanja->getKampanjaID();
        }

        $result4 = $dbBroker->delete($kampanjaBlok, $condition4);

        if ($result && $result1 && $result2 && $result3 && $result4) {
            return true;
        } else {
            return false;
        }
    }

    /*
      public function BlokPremesti($id) {
      $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
      $izabraniBl = $tmpKampanja->getIzabraniBlokovi();
      $izabraniBlokoviZaPrikaz = $tmpKampanja->vratiIzabraneBlokoveZaPrikaz();
      $tmpKampanja->setIzabraniBlokoviZaPrikaz($izabraniBlokoviZaPrikaz);
      //var_dump($izabraniBlokoviZaPrikaz);
      //exit;
      //        $data = $tmpKampanja->izabraniPodaciZaPrikaz();
      //        var_dump($izabraniBl);
      //        exit;
      //        $responseNew = new CoreAjaxResponseInfo();
      //        $responseNew->SetSuccess('true');
      return $tmpKampanja;
      }
     */

    /*
    public function PremestiBlokFirst($campaigneID, $spotID, $blokID, $datumBloka, $datumEmitovanja, $sat, $blokRedosled, $pozicija, $offersCount, $offerNo) {

        $responseNew = new CoreAjaxResponseInfo();
        $dbBroker = new CoreDBBroker();

        if($offersCount==2){//dve ponude
            if ($offerNo == 2) {
                $tmpKampanja = unserialize($_SESSION['tmpKampanja2']);
            } else {
                $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
            }
        }else {
            $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        }

        //$tmpKampanja instanceof TmpKampanjaClass;
        $tempSpot = $tmpKampanja->getSpotByID($spotID);
        if (substr($sat, 0, 1) === '0') {
            $samoSat = (int) substr($sat, 1, 1);
            //var_dump($novBlokPozicija);
        } else {
            $samoSat = (int) substr($sat, 0, 2);
            //var_dump($novBlokPozicija);
        }
        //Dohvatamo period u kome s enalazi blok za dodavanje
        switch (1) {
            case $samoSat >= 6 && $samoSat <= 7:
                $period = 1;
                break;
            case $samoSat >= 8 && $samoSat <= 11:
                $period = 2;
                break;
            case $samoSat >= 12 && $samoSat <= 16:
                $period = 3;
                break;
            case $samoSat >= 17 && $samoSat <= 19:
                $period = 4;
                break;
            case $samoSat >= 20 && $samoSat <= 24:
                $period = 5;
                break;
        }
        $izabraniBlZaPr = $tempSpot['izabraniBlokoviZaPrikaz'];
        $sortiraniDostupniDani = $tempSpot['sortiraniBlokoviSaCenama'][$period];
        $izabraniBlokovi = $tempSpot['izabraniBlokovi'];

        if (in_array($datumEmitovanja, array_keys($sortiraniDostupniDani))) {
            //$izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
            //var_dump($izabraniBlokovi);
            foreach ($izabraniBlokovi[$datumBloka] as $blok => $cena) {
                if ($blokID == substr($blok, 0, strlen($blokID))) {
                    $cenaIzbacenogBloka = $cena;
                    unset($izabraniBlokovi[$datumBloka][$blok]);
                    $novBlokId = (($samoSat - 6) * 4) + $blokRedosled;
                    $query0 = "select max(KB.Redosled) as MaxRedosled
                                    from kampanjablok KB
                                    where KB.BlokID = $novBlokId AND KB.Datum = '" . $datumEmitovanja . "'";
                    $result0 = $dbBroker->selectOneRow($query0);
                    $pozicijaNova = $pozicija > 2 ? ($result0['MaxRedosled'] > 2 ? $result0['MaxRedosled'] + 1 : 3) : $pozicija;
                    $novBlokPozicija = $novBlokId . '/' . $pozicijaNova;

                    if (in_array($novBlokId, array_values($izabraniBlZaPr[$datumEmitovanja])) || in_array($novBlokId, array_values($zauzetiBlZaPr[$datumEmitovanja]))) {
                        $responseNew->SetSuccess('false');
                        $responseNew->SetMessage('Zahtevani blok je zauzet.');
                        return $responseNew;
                    }

                    if (isset($sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija])) {
                        $cenaDodatogBloka = $sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija];
                        $izabraniBlokovi[$datumEmitovanja][$novBlokPozicija] = (float) $cenaDodatogBloka;
                        //$tmpKampanja->setIzabraniBlokovi($izabraniBlokovi);
                        $tempSpot['izabraniBlokovi'] = $izabraniBlokovi;
                        $izabraniBlokoviZaPrikaz = $tmpKampanja->vratiIzabraneBlokoveZaPrikaz($izabraniBlokovi);
                        $tempSpot['izabraniBlokoviZaPrikaz'] = $izabraniBlokoviZaPrikaz;
                        $tmpKampanja->setSpotByID($spotID, $tempSpot);
                        $trenutnaCena = $tmpKampanja->getCampaignePrice();
                        $novaCena = $trenutnaCena - $cenaIzbacenogBloka + $cenaDodatogBloka;
                        $tmpKampanja->setCampaignePrice($novaCena);



                        //$_SESSION['tmpKampanja'] = serialize($tmpKampanja);
                        //return $tmpKampanja;



                        if($offersCount==2){//dve ponude
                            if($offerNo==2) {
                                $_SESSION['tmpKampanja2'] = serialize($tmpKampanja);
                            }else{
                                $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
                            }
                        }else {
                            $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
                        }



                        //return $tmpKampanja;


                        if($offersCount==2){//dve ponude
                            $tmp = unserialize($_SESSION['tmpKampanja']);
                            $tmp2 = unserialize($_SESSION['tmpKampanja2']);

                            $tmp_arr = array(
                                $tmp,
                                $tmp2
                            );

                            return $tmp_arr;
                        }else {
                            return $tmpKampanja;
                        }

                    } else {
                        //echo 'nema mesta';
                        $responseNew->SetSuccess('false');
                        $responseNew->SetMessage('Zahtevani blok je zauzet.');
                        return $responseNew;
                    }
                }
            }
            //var_dump($izabraniBlokovi);
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage('Izabrani datum se ne nalazi u periodu izmedju pocetka i kraja kampanje.');
            return $responseNew;
        }
    }
*/
    /*
    public function PremestiBlokSecond($campaigneID, $spotID, $blokID, $datumBloka, $datumEmitovanja, $sat, $blokRedosled, $pozicija) {
        $responseNew = new CoreAjaxResponseInfo();
        //$campaigneID = 101;
        //$spotID = 407;
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $sortiraniDostupniDani = $this->getDaysForCampaigne($campaigneID);
        $izabraniBlZaPr = $this->getBlocksForCampaigne($campaigneID);
        if (in_array($datumEmitovanja, $sortiraniDostupniDani)) {
            if (substr($sat, 0, 1) === '0') {
                $samoSat = (int) substr($sat, 1, 1);
            } else {
                $samoSat = (int) substr($sat, 0, 2);
            }
            $novBlokId = (($samoSat - 6) * 4) + $blokRedosled;
            $novBlokPozicija = $novBlokId . '/' . $pozicija;
            if (in_array($novBlokId, $izabraniBlZaPr[$datumEmitovanja])) {
                $dbBroker->rollback();
                $responseNew->SetSuccess('false');
                $responseNew->SetMessage('U zahtevanom terminu vec postoji Vasa rekalma.');
                return json_encode($responseNew);
            }


            $spotDetails = $this->GetSpotDetails($spotID);
            $glasID = $spotDetails['glasID'];
            $spotTrajanje = $spotDetails['trajanje'];
            $campaigneDetails = $this->getCampaigneDetails($campaigneID);
            $klijentID = $campaigneDetails['KlijentID'];
            $agencijaID = $campaigneDetails['AgencijaID'];
            $sablonID = $campaigneDetails['SablonID'];
            $radioStanicaID = $campaigneDetails['RadioStanicaID'];
            $delatnostID = $campaigneDetails['DelatnostID'];

            $popust = $campaigneDetails['Popust'];


            $query = "SELECT * 
                          FROM blokzauzece WHERE $radioStanicaID = RadioStanicaID 
                          AND $novBlokId = BlokID 
                          AND Datum = '$datumEmitovanja' 
                          AND ($spotTrajanje > PreostaloSekundi OR ($pozicija = 1 AND ZauzetaPrva = 1) OR ($pozicija = 2 AND ZauzetaDruga = 1))";

            $result = $dbBroker->selectManyRows($query);
            if ($result['numRows'] > 0) {
                $dbBroker->rollback();
                $response->success = false;
                $response->msg = 'Željeni blok je već  zauzet.';
                return json_encode($response);
            } else {
                $query1 = "SELECT * FROM blokzauzece WHERE Datum = '" . $datumEmitovanja . "' AND BlokID = " . $novBlokId . " AND RadioStanicaID = " . $radioStanicaID;
                $result1 = $dbBroker->selectOneRow($query1);
                $blokZauzece = new BlokZauzece();
                $blokZauzece->setGlasIDs($result1['GlasIDs'] . $glasID . ',');
                $blokZauzece->setDelatnostIDs($result1['DelatnostIDs'] . $delatnostID . ',');
                $blokZauzece->setBlokZauzeceID($result1['BlokZauzeceID']);
                $blokZauzece->setZauzetoSekundi($result1['ZauzetoSekundi'] + $spotTrajanje);
                $blokZauzece->setPreostaloSekundi($result1['PreostaloSekundi'] - $spotTrajanje);
                $blokZauzece->setNepotvrdjenoSekundi($result1['NepotvrdjenoSekundi'] + $spotTrajanje);
                if ($pozicija == 1) {
                    $blokZauzece->setZauzetaPrva(1);
                } elseif ($pozicija == 2) {
                    $blokZauzece->setZauzetaDruga(1);
                }

                $condition = " BlokZauzeceID = " . $result1['BlokZauzeceID'] . " AND RadioStanicaID = " . $radioStanicaID;

                $result2 = $dbBroker->update($blokZauzece, $condition);


                $query0 = "select max(KB.Redosled) as MaxRedosled
                                    from kampanjablok KB
                                    where KB.BlokID = $novBlokId AND KB.RadioStanicaID = $radioStanicaID AND KB.Datum = '" . $datumEmitovanja . "'";
                $result0 = $dbBroker->selectOneRow($query0);
                $pozicijaNova = $pozicija > 2 ? ($result0['MaxRedosled'] > 2 ? $result0['MaxRedosled'] + 1 : 3) : $pozicija;
                //Sredi popust kod cene
                //$discount = $this->CalculateDiscount($klijentID, $agencijaID, $sablonID);
                $discount = $popust;
                $newPriceOfBlock = $this->getPriceForBlock($novBlokId, $spotTrajanje, $datumEmitovanja, $radioStanicaID, $discount, $pozicijaNova);
                $kampanjaBlok = new KampanjaBlok();
                $kampanjaBlok->setRadioStanicaID($radioStanicaID);
                $kampanjaBlok->setKampanjaID($campaigneID);
                $kampanjaBlok->setSpotID($spotID);
                $kampanjaBlok->setBlokID($novBlokId);
                $kampanjaBlok->setDatum($datumEmitovanja);
                $kampanjaBlok->setRedosled($pozicijaNova);
                $kampanjaBlok->setCenaEmitovanja($newPriceOfBlock);
                $kampanjaBlok->setGlasID($glasID);
                $kampanjaBlok->setDelatnostID($delatnostID);
                $result3 = $dbBroker->insert($kampanjaBlok);

                //Brisanje
                //Dohvatimo detalje starog spota sa koga se brise polje
                $condition2 = " KampanjaID = $campaigneID AND Datum = '$datumBloka' AND BlokID = $blokID";
                $query5 = "select KampanjaBlokID, SpotID, Redosled, CenaEmitovanja from kampanjablok where $condition2";
                $result6 = $dbBroker->selectOneRow($query5);
                $oldSpotID = $result6['SpotID'];
                $oldPositionOfBlock = $result6['Redosled'];
                $oldSpotDetails = $this->GetSpotDetails($oldSpotID);
                $oldSpotDuration = $oldSpotDetails['trajanje'];
                $query4 = "select * from blokzauzece where  BlokID = " . $blokID . " AND Datum = '" . $datumBloka . "' AND RadioStanicaID = " . $radioStanicaID;
                $result4 = $dbBroker->selectOneRow($query4);

                $blokZauzece1 = new BlokZauzece();
                $blokZauzece1->setZauzetoSekundi($result4['ZauzetoSekundi'] - $oldSpotDuration);
                $blokZauzece1->setPreostaloSekundi($result4['PreostaloSekundi'] + $oldSpotDuration);
                $blokZauzece1->setNepotvrdjenoSekundi($result4['NepotvrdjenoSekundi'] - $oldSpotDuration);
                if ($oldPositionOfBlock == 1) {
                    $blokZauzece1->setZauzetaPrva(0);
                } elseif ($oldPositionOfBlock == 2) {
                    $blokZauzece1->setZauzetaDruga(0);
                } else {
                    
                }
                $condition1 = " BlokZauzeceID = " . $result4['BlokZauzeceID'];
                $result5 = $dbBroker->update($blokZauzece1, $condition1);


                $oldPriceOfBlock = $result6['CenaEmitovanja'];

                $kampanjaBlok1 = new KampanjaBlok();
                $dbBroker->delete($kampanjaBlok1, $condition2);


                $kampanja = new Kampanja();
                $oldPrice = $campaigneDetails['CenaUkupno'];
                $newPrice = $oldPrice - $oldPriceOfBlock + $newPriceOfBlock;
                $kampanja->setCenaUkupno($newPrice);
                $kampanja->setKampanjaID($campaigneID);
                $condition3 = " KampanjaID = " . $campaigneID;
                $result7 = $dbBroker->update($kampanja, $condition3);

                if ($result && $result1 && $result2 && $result3 && $result4 && $result5 && $result6 && $result7) {
                    $dbBroker->commit();
                    $response = $this->KampanjaPregledEmitovanja($kampanja);
                    $dbBroker->close();
                    return $response;
                } else {
                    $dbBroker->rollback();
                    $response->success = false;
                    $response->msg = CoreError::getError();
                    $dbBroker->close();
                    return json_encode($response);
                }
            }
        } else {
            $dbBroker->rollback();
            $response->success = false;
            $response->msg = 'Izabrani datum se ne nalazi u periodu izmedju pocetka i kraja Vase kampanje.';
            return json_encode($response);
        }
    }*/

    public function BlokObrisiFirst($campaigneID, $spotID, $blokID, $datumBloka, $offersCount, $offerNo) {
        //$responseNew = new CoreAjaxResponseInfo();

        //$campaigneID+=0;
        $spotID+=0;
        $blokID+=0;
        //$datumBloka=$datumBloka;
        $offersCount+=0;
        $offerNo+=0;


        if($offersCount==2) {//dva predloga
            if ($offerNo == 2) {
                $tmpKampanja = unserialize($_SESSION['tmpKampanja2']);
            } else {
                $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
            }
        }else{
            $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        }


        $tempSpot = $tmpKampanja->getSpotByID($spotID);
        $izabraniBlokovi = $tempSpot['izabraniBlokovi'];
        $blokPoz = $blokID . '/';
        $len = strlen($blokPoz);
        foreach ($izabraniBlokovi[$datumBloka] as $blok => $cena) {
            $blokSub = substr($blok, 0, $len);
            if ($blokSub == $blokPoz) {
                $blokZaIzbacivanje = $blok;
                $cenaIzvacenogBloka = $cena;
                break;
            }
        }
        unset($izabraniBlokovi[$datumBloka][$blokZaIzbacivanje]);
        $tempSpot['izabraniBlokovi'] = $izabraniBlokovi;
        $izabraniBlokoviZaPrikaz = $tmpKampanja->vratiIzabraneBlokoveZaPrikaz($izabraniBlokovi);
        $tempSpot['izabraniBlokoviZaPrikaz'] = $izabraniBlokoviZaPrikaz;
        $tmpKampanja->setSpotByID($spotID, $tempSpot);
        $trenutnaCena = $tmpKampanja->getCampaignePrice();
        $novaCena = $trenutnaCena - $cenaIzvacenogBloka;
        $tmpKampanja->setCampaignePrice($novaCena);



        /*
        $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
        return $tmpKampanja;*/

        if($offersCount==2) {//dva predloga
            if($offerNo==2) {
                $_SESSION['tmpKampanja2'] = serialize($tmpKampanja);
            }else{
                $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
            }
        }else{
            $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
        }





        $response->success = true;
        $response->data->capmaignePrice = $novaCena;

        //$dbBroker->close();

        return json_encode($response);



        /*
        if($offersCount==2) {//dva predloga
            $tmp = unserialize($_SESSION['tmpKampanja']);
            $tmp2 = unserialize($_SESSION['tmpKampanja2']);

            $tmp_arr = array(
                $tmp,
                $tmp2
            );

            return $tmp_arr;
        }else{
            return $tmpKampanja;
        }*/


    }

    public function BlokObrisiSecond($campaigneID, $spotID, $blokID, $datumBloka) {



        $response = new stdClass();


        $dbBroker = new CoreDBBroker();
        $query_pom = "SELECT * FROM kampanja WHERE KampanjaID = " . $campaigneID;
        $result_pom = $dbBroker->selectOneRow($query_pom);
        $statusKampanjaID = $result_pom['StatusKampanjaID'];
        if($statusKampanjaID==2){
            global $korisnik_init;
            if($korisnik_init['tipKorisnik']==3){
                $response->success = false;
                $response->msg = 'Nemože se obrisati blok potvrdjene kampanje!';
                return json_encode($response);
            }
        }





        $campaigneID+=0;
        $spotID+=0;
        $blokID+=0;
        //$datumBloka=$datumBloka;

        $responseNew = new CoreAjaxResponseInfo();



        $datumBloka=date('Y-m-d', strtotime($datumBloka));


        $now = date('Y-m-d', time());
        if (strtotime($datumBloka) < strtotime($now)) {
            $response->success = false;
            $response->msg = 'Nemože se promeniti emitovanje za datum koji je prošao!';
            return json_encode($response);
        }



        $sortiraniDostupniDani = $this->getDaysForCampaigne($campaigneID);
        if (in_array($datumBloka, $sortiraniDostupniDani)) {

            //$dbBroker = new CoreDBBroker();
            $dbBroker->beginTransaction();

            $campaigneDetails = $this->getCampaigneDetails($campaigneID);
            $spotDetails = $this->GetSpotDetails($spotID);
            $radioStanicaID = $campaigneDetails['RadioStanicaID'];
            $klijentID = $campaigneDetails['KlijentID'];
            $agencijaID = $campaigneDetails['AgencijaID'];
            $sablonID = $campaigneDetails['SablonID'];
            $spotDuration = $spotDetails['trajanje'];
            $query1 = "select * from blokzauzece where  BlokID = " . $blokID . " AND Datum = '" . $datumBloka . "' AND RadioStanicaID = " . $radioStanicaID;
            $result1 = $dbBroker->selectOneRow($query1);

            $condition2 = " KampanjaID = $campaigneID AND SpotID = $spotID AND Datum = '$datumBloka' AND BlokID = $blokID";
            $query2 = "select KampanjaBlokID, CenaEmitovanja, Redosled from kampanjablok where $condition2";
            $result3 = $dbBroker->selectOneRow($query2);
            $priceOfBlock = $result3['CenaEmitovanja'];
            $positionOfBlock = $result3['Redosled'];

            $blokZauzece = new BlokZauzece();
            $blokZauzece->setZauzetoSekundi($result1['ZauzetoSekundi'] - $spotDuration);
            $blokZauzece->setPreostaloSekundi($result1['PreostaloSekundi'] + $spotDuration);
            $blokZauzece->setNepotvrdjenoSekundi($result1['NepotvrdjenoSekundi'] - $spotDuration);
            if ($positionOfBlock == 1) {
                $blokZauzece->setZauzetaPrva(0);
            } elseif ($positionOfBlock == 2) {
                $blokZauzece->setZauzetaDruga(0);
            } else {

            }
            $condition1 = " BlokZauzeceID = " . $result1['BlokZauzeceID'];
            $result2 = $dbBroker->update($blokZauzece, $condition1);



            $kampanjaBlok = new KampanjaBlok();
            $result4 = $dbBroker->delete($kampanjaBlok, $condition2);


            $kampanja = new Kampanja();
            $oldPrice = $campaigneDetails['CenaUkupno'];
            //$discount = $this->CalculateDiscount($klijentID, $agencijaID, $sablonID);
            //$priceOfBlock = number_format($priceOfBlock * (100 - $discount) / 100, 2);
            $newPrice = $oldPrice - $priceOfBlock;
            $kampanja->setCenaUkupno($newPrice);
            $kampanja->setKampanjaID($campaigneID);
            $condition3 = " KampanjaID = " . $campaigneID;
            $result4 = $dbBroker->update($kampanja, $condition3);

            if ($result1 && $result2 && $result3 && $result4) {
                $dbBroker->commit();
                //$response = $this->KampanjaPregledEmitovanja($kampanja);

                $response->success = true;
                $response->data->capmaignePrice = $newPrice;

                $dbBroker->close();

                return json_encode($response);
            } else {
                $dbBroker->rollback();
                $response->success = false;
                $response->msg = CoreError::getError();
                $dbBroker->close();
                return json_encode($response);
            }



        } else {
            $response->success = false;
            $response->msg = 'Datum ne pripada kampanji!';
            return json_encode($response);
        }




    }

    public function DodajBlokSecond($campaigneID, $spotID, $datumEmitovanja, $blokID, $pozicija) {





        $response = new stdClass();


        $dbBroker = new CoreDBBroker();
        $query_pom = "SELECT * FROM kampanja WHERE KampanjaID = " . $campaigneID;
        $result_pom = $dbBroker->selectOneRow($query_pom);
        $statusKampanjaID = $result_pom['StatusKampanjaID'];

        $tipPlacanjaID = $result_pom['TipPlacanjaID'];

        if($statusKampanjaID==2){
            global $korisnik_init;
            if($korisnik_init['tipKorisnik']==3){
                $response->success = false;
                $response->msg = 'Nemože se dodati blok potvrdjenoj kampanji!';
                return json_encode($response);
            }
        }










        $datumEmitovanja=date('Y-m-d', strtotime($datumEmitovanja));
        $now = date('Y-m-d', time());
        if (strtotime($datumEmitovanja) < strtotime($now)) {
            $response->success = false;
            $response->msg = 'Nemože se promeniti emitovanje za datum koji je prošao!';
            return json_encode($response);
        }





        //$dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();

        $sortiraniDostupniDani = $this->getDaysForCampaigne($campaigneID);
        $izabraniBlZaPr = $this->getBlocksForCampaigne($campaigneID);
        if (in_array($datumEmitovanja, $sortiraniDostupniDani)) {
            if (in_array($blokID, $izabraniBlZaPr[$datumEmitovanja])) {
                $dbBroker->rollback();
                $response->success = false;
                $response->msg = 'U zahtevanom terminu vec postoji Vasa rekalma';
                return json_encode($response);
            }


            $spotDetails = $this->GetSpotDetails($spotID);
            $glasID = $spotDetails['glasID'];
            $spotTrajanje = $spotDetails['trajanje'];
            $campaigneDetails = $this->getCampaigneDetails($campaigneID);
            $klijentID = $campaigneDetails['KlijentID'];
            $agencijaID = $campaigneDetails['AgencijaID'];
            $sablonID = $campaigneDetails['SablonID'];
            $radioStanicaID = $campaigneDetails['RadioStanicaID'];
            $delatnostID = $campaigneDetails['DelatnostID'];



            $popust = $campaigneDetails['Popust'];

            $naziv = $campaigneDetails['Naziv'];




            $query = "SELECT * 
                          FROM blokzauzece WHERE $radioStanicaID = RadioStanicaID 
                          AND $blokID = BlokID 
                          AND Datum = '$datumEmitovanja' 
                          AND ($spotTrajanje > PreostaloSekundi OR ($pozicija = 1 AND ZauzetaPrva = 1) OR ($pozicija = 2 AND ZauzetaDruga = 1))";

            $result = $dbBroker->selectManyRows($query);
            if ($result['numRows'] > 0) {
                $dbBroker->rollback();
                $response->success = false;
                $response->msg = 'Željeni blok je već  zauzet.';
                return json_encode($response);
            } else {



                $query1 = "SELECT * FROM blokzauzece WHERE Datum = '" . $datumEmitovanja . "' AND BlokID = " . $blokID . " AND RadioStanicaID = " . $radioStanicaID;
                $result1 = $dbBroker->selectOneRow($query1);
                $blokZauzece = new BlokZauzece();
                $blokZauzece->setGlasIDs($result1['GlasIDs'] . $glasID . ',');
                $blokZauzece->setDelatnostIDs($result1['DelatnostIDs'] . $delatnostID . ',');
                $blokZauzece->setBlokZauzeceID($result1['BlokZauzeceID']);
                $blokZauzece->setZauzetoSekundi($result1['ZauzetoSekundi'] + $spotTrajanje);
                $blokZauzece->setPreostaloSekundi($result1['PreostaloSekundi'] - $spotTrajanje);
                $blokZauzece->setNepotvrdjenoSekundi($result1['NepotvrdjenoSekundi'] + $spotTrajanje);
                //Proveravamo ukoliko je premium blok onda pozicija jedino moze biti 1
                if (($blokID % 2) == 0) {
                    $pozicija = 1;
                } 
                if ($pozicija == 1) {
                    $blokZauzece->setZauzetaPrva(1);
                } elseif ($pozicija == 2) {
                    $blokZauzece->setZauzetaDruga(1);
                }

                $condition = " BlokZauzeceID = " . $result1['BlokZauzeceID'] . " AND RadioStanicaID = " . $radioStanicaID;

                $result2 = $dbBroker->update($blokZauzece, $condition);


                $query0 = "select max(KB.Redosled) as MaxRedosled
                                    from kampanjablok KB
                                    where KB.BlokID = $blokID AND KB.RadioStanicaID = $radioStanicaID AND KB.Datum = '" . $datumEmitovanja . "'";
                $result0 = $dbBroker->selectOneRow($query0);
                $pozicijaNova = $pozicija > 2 ? ($result0['MaxRedosled'] > 2 ? $result0['MaxRedosled'] + 1 : 3) : $pozicija;
                //Sredi popust kod cene





                $query_pom = "SELECT * FROM kampanjablok WHERE Datum = '" . $datumEmitovanja . "' AND BlokID = " . $blokID . " AND RadioStanicaID = " . $radioStanicaID." AND Redosled IN (".($pozicijaNova-1).",".($pozicijaNova+1).") AND (GlasID = ".$glasID." OR DelatnostID = ".$delatnostID.")";

                //$query_pom = "SELECT * FROM kampanjablok WHERE Datum = '" . $datumEmitovanja . "' AND BlokID = " . $blokID . " AND RadioStanicaID = " . $radioStanicaID;


/*
                $dbBroker->rollback();
                $response->success = false;
                $response->msg = $query_pom;
                return json_encode($response);
*/

                $result_pom = $dbBroker->selectOneRow($query_pom);
                if($result_pom){
                    $dbBroker->rollback();
                    $response->success = false;
                    $response->msg = 'Glas ili delatnost se poklapaju.';
                    return json_encode($response);
                }
/*radi i ovo i ovo iznad ako ima jedan ili vise redova
                $result_pom = $dbBroker->selectManyRows($query_pom);
                if ($result_pom['numRows'] > 0) {
                    $dbBroker->rollback();
                    $response->success = false;
                    $response->msg = 'Glas ili delatnost se poklapaju.';
                    return json_encode($response);
                }*/



                //$discount = $this->CalculateDiscount($klijentID, $agencijaID, $sablonID);
                $discount = $popust;
                $priceOfBlock = $this->getPriceForBlock($blokID, $spotTrajanje, $datumEmitovanja, $radioStanicaID, $discount, $pozicijaNova);


                if($tipPlacanjaID==8){
                    $priceOfBlock = (float) $priceOfBlock * 2;
                }


                $kampanjaBlok = new KampanjaBlok();
                $kampanjaBlok->setRadioStanicaID($radioStanicaID);
                $kampanjaBlok->setKampanjaID($campaigneID);
                $kampanjaBlok->setSpotID($spotID);
                $kampanjaBlok->setBlokID($blokID);
                $kampanjaBlok->setDatum($datumEmitovanja);
                $kampanjaBlok->setRedosled($pozicijaNova);
                $kampanjaBlok->setCenaEmitovanja($priceOfBlock);
                $kampanjaBlok->setGlasID($glasID);
                $kampanjaBlok->setDelatnostID($delatnostID);
                $result3 = $dbBroker->insert($kampanjaBlok);

                $kampanja = new Kampanja();
                $oldPrice = $campaigneDetails['CenaUkupno'];
                $newPrice = $oldPrice + $priceOfBlock;
                $kampanja->setCenaUkupno($newPrice);
                $kampanja->setKampanjaID($campaigneID);
                $condition1 = " KampanjaID = " . $campaigneID;
                $result4 = $dbBroker->update($kampanja, $condition1);
                if ($result && $result1 && $result2 && $result3 && $result4) {
                    $dbBroker->commit();









                    //$response = $this->KampanjaPregledEmitovanja($kampanja);


                    $spots_arr=$this->KampanjaVratiSpotove($campaigneID);

                    $TF=false;
                    foreach ($spots_arr as $key => $spot) {
                        if($spot['SpotID']==$spotID){
                            $TF=true;
                            $response->data->spot = $spot;

                            $response->data->spot['pozicija']=$pozicijaNova;

                            break;
                        }
                    }

                    if(!$TF){

                        $response->success = false;
                        $response->msg = "GRESKA 65993!!!!!!!!!";
                        $dbBroker->close();
                        return json_encode($response);
                    }




                    $response->success = true;
                    $response->data->capmaignePrice = $newPrice;
                    $response->data->capmaigneName = $naziv;
                    $dbBroker->close();

                    return json_encode($response);


                } else {
                    $dbBroker->rollback();
                    $response->success = false;
                    $response->msg = CoreError::getError();
                    $dbBroker->close();
                    return json_encode($response);
                }

//                $dbBroker->commit();
//                $response = $this->KampanjaPregledEmitovanja($kampanja);
//                return $response;
            }
        } else {
            $response->success = false;
            $response->msg = 'Datum ne pripada kampanji!';
            return json_encode($response);
        }
    }

    public function DodajBlokFirst($campaigneID, $spotID, $datumEmitovanja, $blokID, $pozicija, $offersCount, $offerNo) {
        $responseNew = new CoreAjaxResponseInfo();
        $dbBroker = new CoreDBBroker();

        if($offersCount==2){//dve ponude
            if($offerNo==2) {
                $tmpKampanja = unserialize($_SESSION['tmpKampanja2']);
            }else{
                $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
            }
        }else{
            $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        }







        //$tmpKampanja instanceof TmpKampanjaClass;
        $tempSpot = $tmpKampanja->getSpotByID($spotID);
        //Dohvatamo period u kome s enalazi blok za dodavanje
        $sat = 6+(int)((($blokID-1) >= 0 ? ($blokID -1 ) : 0)/4);
        switch (1) {
            case $sat >= 6 && $sat <= 7:
                $period = 1;
                break;
            case $sat >= 8 && $sat <= 11:
                $period = 2;
                break;
            case $sat >= 12 && $sat <= 16:
                $period = 3;
                break;
            case $sat >= 17 && $sat <= 19:
                $period = 4;
                break;
            case $sat >= 20 && $sat <= 24:
                $period = 5;
                break;
        }
        $izabraniBlZaPr = $tempSpot['izabraniBlokoviZaPrikaz'];
        $sortiraniDostupniDani = $tempSpot['sortiraniBlokoviSaCenama'][$period];
        $izabraniBlokovi = $tempSpot['izabraniBlokovi'];
        if (in_array($datumEmitovanja, array_keys($sortiraniDostupniDani))) {

            //$novBlokId = (($samoSat - 6) * 4) + $blok;
            $radioStanicaID = $tmpKampanja->getRadioStanicaID();
            $query0 = "select max(KB.Redosled) as MaxRedosled
                                    from kampanjablok KB
                                    where KB.BlokID = $blokID AND KB.RadioStanicaID = $radioStanicaID AND KB.Datum = '" . $datumEmitovanja . "'";
            $result0 = $dbBroker->selectOneRow($query0);
            $pozicijaNova = $pozicija > 2 ? ($result0['MaxRedosled'] > 2 ? $result0['MaxRedosled'] + 1 : 3) : $pozicija;
            $novBlokPozicija = $blokID . '/' . $pozicijaNova;

            if (in_array($blokID, $izabraniBlZaPr[$datumEmitovanja])) { //ovde za uslov treba dodati proveru da li u zahtevanom bloku vec ima izabrana nasa reklama


                /*
                $responseNew->SetSuccess('false');
                $responseNew->SetMessage('U zahtevanom terminu vec postoji Vasa rekalma.');
                return $responseNew;
*/

                $response->success = false;
                $response->msg = 'U zahtevanom terminu vec postoji Vasa rekalma.';
                return json_encode($response);


            }


            //echo ($datumEmitovanja." - ".$novBlokPozicija." = ".$sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija]);
            /*var_dump($sortiraniDostupniDani);
exit;*/

            if ($sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija] > 0) {
                $cenaNovogBloka = $sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija];
                $izabraniBlokovi[$datumEmitovanja][$novBlokPozicija] = (float) $cenaNovogBloka;
                //$izabraniBlokoviZaPrikaz = $tempSpot['izabraniBlokoviZaPrikaz'];
                //$sortiraniDostupniDani = $tempSpot['sortiraniBlokoviSaCenama'];
                //$izabraniBlokovi = $tempSpot['izabraniBlokovi'];
                $tempSpot['izabraniBlokovi'] = $izabraniBlokovi;
                //$tmpKampanja->setIzabraniBlokovi($izabraniBlokovi);
                $izabraniBlokoviZaPrikaz = $tmpKampanja->vratiIzabraneBlokoveZaPrikaz($izabraniBlokovi);
                //$tmpKampanja->setIzabraniBlokoviZaPrikaz($izabraniBlokoviZaPrikaz);
                $tempSpot['izabraniBlokoviZaPrikaz'] = $izabraniBlokoviZaPrikaz;
                $tmpKampanja->setSpotByID($spotID, $tempSpot);
                $trenutnaCena = $tmpKampanja->getCampaignePrice();
                //$tmpKampanja->CalculateDiscount();
                //$discount = $tmpKampanja->CalculateDiscount();
                $novaCena = $trenutnaCena + $cenaNovogBloka;
                $tmpKampanja->setCampaignePrice($novaCena);



                if($offersCount==2){//dve ponude
                    if($offerNo==2) {
                        $_SESSION['tmpKampanja2'] = serialize($tmpKampanja);
                    }else{
                        $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
                    }
                }else{
                    $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
                }










                        //$spots_arr=$this->KampanjaVratiSpotove($campaigneID);





/*
                $tempSpot = array();
                $tempSpot['rb'] = $spot_rb;
                $tempSpot['finish'] = 0;
                $tempSpot['control'] = 0;
                $tempSpot['spotTrajanje'] = $spot->getSpotTrajanje();
                $tempSpot['spotName'] = $spot->getSpotName();
                $tempSpot['spotID'] = $spotID;
                $tempSpot['glasID'] = $spot->getGlasID();
                $tempSpot['premiumBlokovi'] = $spot->getPremiumBlokovi();
                $tempSpot['numberDays'] = $spot->getNumberDays();
                $tempSpot['days'] = $spot->getDays();
                $tempSpot['maksimalanBrojBlokova'] = $tempSpot['numberDays'] * $spot->getUcestalotSuma(); //block/position
                $tempSpot['trenutnaCena'] = 0;
                $tempSpot['ukupnoZauzetoBlokova'] = 0;//  block/position
                $tempSpot['izabraniBlokovi'] = array();
                $tempSpot['zauzetiBlokoviZaPrikaz'] = $this->zauzetiBlokoviZaPrikaz;
                $tempSpot['izabraniBlokoviZaPrikaz'] = array();
                $tempSpot['sortiraniBlokoviSaCenama'] =     $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
                $tempSpot['sortiraniDostupniZeljeniDani'] = $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
*/




                        $spot=$this->GetSpotDetails2($spotID);

                        $spot['rb']=$tempSpot['rb'];

                        $response->data->spot = $spot;
                        $response->data->spot['pozicija']=$pozicijaNova;


                        $response->success = true;
                        $response->data->capmaignePrice = $novaCena;
                        $response->data->capmaigneName = $tmpKampanja->getNaziv();
                        $dbBroker->close();

                        return json_encode($response);




















                //return $tmpKampanja;

/*
                if($dvaPredloga) {
*/







                if($offersCount==2) {//dve ponude

                    $tmp = unserialize($_SESSION['tmpKampanja']);
                    $tmp2 = unserialize($_SESSION['tmpKampanja2']);

                    $tmp_arr = array(
                        $tmp,
                        $tmp2
                    );

                    return $tmp_arr;

                }else{
                    return $tmpKampanja;

                }




                /*
                }else{
                    return $tmpKampanja;
                }*/



            } else {


                $response->success = false;




                //$responseNew->SetSuccess('false');

                if(($tempSpot['premiumBlokovi']==1 && $blokID%2==0) || ($tempSpot['premiumBlokovi']==2 && $blokID%2==1)){
                    //$responseNew->SetMessage('Spot '.$tempSpot['spotName'].' nije dozvoljen za '.(($blokID%2==0)?'PREMIUM':'STANDARDNU').' vrstu bloka!!.');
                    $response->msg = 'Spot '.$tempSpot['spotName'].' nije dozvoljen za '.(($blokID%2==0)?'PREMIUM':'STANDARDNU').' vrstu bloka!!.';
                }else{
                    //$responseNew->SetMessage('Zahtevani pozicija emitovanja u izabranom bloku je zauzeta.');
                    $response->msg = 'Zahtevani pozicija emitovanja u izabranom bloku je zauzeta.';
                }

                //return $responseNew;
                return json_encode($response);
            }
        } else {
            /*
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage('Spot '.$tempSpot['spotName'].' nije dozvoljen za izabrani datum!!');
            return $responseNew;*/

            $response->success = false;
            $response->msg = 'Spot '.$tempSpot['spotName'].' nije dozvoljen za izabrani datum!!';
            return json_encode($response);

        }
    }

    public function DodajBlokNedeljniSablon($datumEmitovanja, $sat, $blok, $pozicija) {
        $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        $daniZaEmitovanje = $tmpKampanja->getSortiraniDostupniZeljeniDani();
        //var_dump($daniZaEmitovanje);
        //exit();
        foreach ($daniZaEmitovanje as $key => $value) {
            $result = $this->DodajBlok($key, $sat, $blok, $pozicija);
        }
        return $result;
        //var_dump($daniZaEmitovanje);
        //exit;
        /*
          $izabraniBlZaPr = $tmpKampanja->getIzabraniBlokoviZaPrikaz();
          $sortiraniDostupniDani = $tmpKampanja->getSortiraniBlokoviSaCenama();
          $izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
          if (in_array($datumEmitovanja, array_keys($sortiraniDostupniDani))) {

          if(substr($sat, 0, 1) === '0') {
          $samoSat = (int) substr($sat, 1, 1);
          $novBlokId = (($samoSat-6)*4) + $blok;
          $novBlokPozicija = $novBlokId.'/'.$pozicija;
          } else {
          $samoSat = (int) substr($sat, 0, 2);
          $novBlokId = (($samoSat-6)*4) + $blok;
          $novBlokPozicija = $novBlokId.'/'.$pozicija;
          }

          if(in_array($novBlokId, $izabraniBlZaPr[$datumEmitovanja])) { //ovde za uslov treba dodati proveru da li u zahtevanom bloku vec ima izabrana nasa reklama
          $responseNew->SetSuccess('false');
          $responseNew->SetMessage('U zahtevanom terminu vec postoji Vasa rekalma.');
          return $responseNew;
          }

          if($sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija] > 0) {
          $cenaNovogBloka = $sortiraniDostupniDani[$datumEmitovanja][$novBlokPozicija];
          $izabraniBlokovi[$datumEmitovanja][$novBlokPozicija] = (float) $cenaNovogBloka;
          $tmpKampanja->setIzabraniBlokovi($izabraniBlokovi);
          $izabraniBlokoviZaPrikaz = $tmpKampanja->vratiIzabraneBlokoveZaPrikaz();
          $tmpKampanja->setIzabraniBlokoviZaPrikaz($izabraniBlokoviZaPrikaz);
          $trenutnaCena = $tmpKampanja->getTrenutnaCena();
          $novaCena = $trenutnaCena + $cenaNovogBloka;
          $tmpKampanja->setTrenutnaCena($novaCena);
          $_SESSION['tmpKampanja'] = serialize($tmpKampanja);
          return $tmpKampanja;
          } else {
          $responseNew->SetSuccess('false');
          $responseNew->SetMessage('Zahtevani termin emitovanja je zauzet.');
          return $responseNew;
          }

          } else {
          $responseNew->SetSuccess('false');
          $responseNew->SetMessage('Izabrani datum se ne nalazi u periodu izmedju pocetka i kraja Vase kampanje.');
          return $responseNew;
          }
         */
    }

    public function KampanjaIzSablona(KampanjaZahtev $kampanjaZahtev, $ponudaId = NULL, $sablonId) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $query1 = "SELECT *
            FROM sablon S
            WHERE S.Aktivan = 1 AND S.SablonID = " . $sablonId;
        $result1 = $dbBroker->selectOneRow($query1);
        $kampanjaZahtev->setNaziv("Sablon - " . $result1['Naziv']);




        $result = $dbBroker->insert($kampanjaZahtev);
        $spotArray = $kampanjaZahtev->getSpotArray();





        $daniZaEmitovanje=$result1['DaniZaEmitovanje'];
        $ucestalost=$result1['Ucestalost'];

        $daniZaEmitovanje_arr=explode(",",$daniZaEmitovanje);
        $ucestalost_arr=explode(",",$ucestalost);



        $dani = array();
        $periodi = array();
        $spotUcestalost = array();
        $daniStampa = "[";
        $periodiStampa = "[";


        foreach($daniZaEmitovanje_arr as $value){
            array_push($dani, $value);
            $daniStampa .= $value;
            $daniStampa .= ",";
        }

        $i=0;
        foreach($ucestalost_arr as $value){
            $i++;
            if($value>0) {
                $spotUcestalost[$i] = $value;
                array_push($periodi, $i);
                $periodiStampa .= $i;
                $periodiStampa .= ",";
            }
        }

        $daniStampa = substr($daniStampa, 0, -1);
        $periodiStampa = substr($periodiStampa, 0, -1);
        $daniStampa .= "]";
        $periodiStampa .= "]";


        //send_respons_boban($daniStampa);





        //formirmao niz spotova kako bi se registrovao i ID nakon inserta
        $spotArrayAfterInsert = array();
        foreach ($spotArray as $spot) {



            $spot->setDaniZaEmitovanje($daniStampa);
            $spot->setPeriodiZaEmitovanje($periodiStampa);
            $spot->setDani($dani);
            $spot->setPeriodi($periodi);
            $spot->setSpotUcestalost($spotUcestalost);
            $spot->setUcestalostSuma();





            $result1 = $dbBroker->insert($spot);
            $result = $result && $result1;
            $spotID = $dbBroker->getLastInsertedId();
            $spot->setSpotID($spotID);
            array_push($spotArrayAfterInsert, $spot);

        }

        $responseNew = new CoreAjaxResponseInfo();

        if ($result) {



            $dbBroker->commit();
            $dbBroker->close();
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
            return $responseNew; //Ovde treba videti sta treba da se vrati ukoliko se zahtev ne snimi kako treba
        }







        $object = new TmpKampanjaClass($kampanjaZahtev->getKlijentID(), $kampanjaZahtev->getAgencijaID(), $kampanjaZahtev->getRadioStanicaID(), $kampanjaZahtev->getBrendID(), $kampanjaZahtev->getNaziv(), $kampanjaZahtev->getDatumPocetka(), $kampanjaZahtev->getDatumKraja(), $kampanjaZahtev->getBudzet(), $kampanjaZahtev->getNapomena(), $spotArrayAfterInsert, $ponudaId, $sablonId, $kampanjaZahtev->getTipPlacanjaID());
        //Ovde dodajemo podatke o spotovima u tmp kampanju
        //$object->setSpotArray($spotArrayAfterInsert);
        $tmp = $object->getTmpKampanja();

        if (isset($_SESSION['tmpKampanja'])) {
            unset($_SESSION['tmpKampanja']);
        }
        $_SESSION['tmpKampanja'] = serialize($object);

        return $tmp;
    }

    public function KampanjaIzSablonaOld($sablonId, $trajanjeSpota, $klijentId) {
        $dbBroker = new CoreDBBroker();
        $query = "SELECT S.Naziv as Naziv,
                    S.Popust as Popust,
                    S.KorisnikID as KorisnikID,
                    s.DatumPocetak,
                    s.DatumZavrsetak
                    FROM sablon S
                    WHERE S.Aktivan = 1 AND S.SablonID = " . $sablonId;
        $result = $dbBroker->selectOneRow($query);

        $query1 = "SELECT S.Naziv as Naziv,
                    SB.*
                    from sablon S
                    inner join sablonblok SB on S.SablonID = SB.SablonID
                    where S.SablonID =" . $sablonId;
        $result1 = $dbBroker->selectManyRows($query1);
        $ukupnaCena = 0;
        $error = false;
        $allData = array();

        $dbBroker->beginTransaction();
        foreach ($result1['rows'] as $row) {
            $data = array();
            $testDay = date("N", strtotime($row['Datum']));
            if (in_array($testDay, array(6, 7))) {
                $vikend = 1;
            } else {
                $vikend = 0;
            }

            $query2 = "select C.Cena
                       from cenovnik C
                       inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID 
                       where $trajanjeSpota between KC.TrajanjeOd 
                       and KC.TrajanjeDo AND C.BlokID = " . $row['BlokID'] . "
                       and C.Vikend = $vikend";
            $result2 = $dbBroker->selectOneRow($query2);
            $price = number_format($result2['Cena'] - ($result2['Cena'] * $result['Popust'] / 100), 2);
            if ($row['Redosled'] == 1) {
                $price = (float) $price * 1.2;
                $query3 = "SELECT * FROM blokzauzece WHERE Datum = '" . $row['Datum'] . "' AND BlokID = " . $row['BlokID'] . " AND ZauzetaPrva = 0";
            } else if ($row['Redosled'] == 2) {
                $price = (float) $price;
                $query3 = "SELECT * FROM blokzauzece WHERE Datum = '" . $row['Datum'] . "' AND BlokID = " . $row['BlokID'] . " AND ZauzetaDruga = 0";
            } else {
                $price = (float) $price * 1;
                $query3 = "SELECT * FROM blokzauzece WHERE Datum = '" . $row['Datum'] . "' AND BlokID = " . $row['BlokID'];
            }

            $ukupnaCena += $price;
            $data['BlokID'] = $row['BlokID'];
            $data['Datum'] = $row['Datum'];
            $data['Redosled'] = $row['Redosled'];
            $data['Cena'] = $price;
            $allData[] = $data;

            $result3 = $dbBroker->selectOneRow($query3);

            if ($result3) {
                if ($result3['PreostaloSekundi'] >= $trajanjeSpota) {
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setZauzetoSekundi($result3['ZauzetoSekundi'] + $trajanjeSpota);
                    $blokZauzece->setPreostaloSekundi($result3['PreostaloSekundi'] - $trajanjeSpota);
                    $blokZauzece->setNepotvrdjenoSekundi($result3['NepotvrdjenoSekundi'] + $trajanjeSpota);
                    if ($row['Redosled'] == 1) {
                        $blokZauzece->setZauzetaPrva(1);
                        /*
                          $query4 = "UPDATE blokzauzece SET ZauzetoSekundi = ".$result3['ZauzetoSekundi']+$trajanjeSpota." ,
                          PreostaloSekundi = ".$result3['PreostaloSekundi']-$trajanjeSpota." ,
                          ZauzetaPrva = 1
                          WHERE Datum = '".$row['Datum']."' AND BlokID = ".$row['BlokID'];
                         */
                    } else if ($row['Redosled'] == 2) {
                        $blokZauzece->setZauzetaDruga(1);
                        /*
                          $query4 = "UPDATE blokzauzece SET ZauzetoSekundi = ".$result3['ZauzetoSekundi']+$trajanjeSpota." ,
                          PreostaloSekundi = ".$result3['PreostaloSekundi']-$trajanjeSpota." ,
                          ZauzetaDruga = 1
                          WHERE Datum = '".$row['Datum']."' AND BlokID = ".$row['BlokID'];
                         */
                    }

                    $condition = " Datum = '" . $row['Datum'] . "' AND BlokID = " . $row['BlokID'];

                    $result4 = $dbBroker->update($blokZauzece, $condition);
                    if ($result4 === false) {
                        $error = true;
                    }
                    unset($blokZauzece);
                } else {
                    $error = true;
                }
            } else {
                $error = true;
            }
        }

        //$vremeZaPotvrdu = date('Y-m-d H:i:s', strtotime($result['VremePostavke'].'+'.$periodZaPotvrdu.' days'));
        $vremeZaPotvrdu = date('Y-m-d H:i:s', strtotime('+2 days'));

        $kampanja = new Kampanja();
        $kampanja->setKlijentID($klijentId);
        $kampanja->setNaziv($result['Naziv']);
        $kampanja->setDatumPocetka($result['DatumPocetak']);
        $kampanja->setDatumKraja($result['DatumZavrsetak']);
        $kampanja->setFinansijskiStatusID(FinansijskiStatus::Nefakturisana);
        $kampanja->setStatusKampanjaID(StatusKampanjaEnum::Uneta);
        $kampanja->setKorisnikID($result['KorisnikID']);
        $kampanja->setCenaUkupno($ukupnaCena);
        $kampanja->setSpotUkupno($trajanjeSpota);
        $kampanja->setVremeZaPotvrdu($vremeZaPotvrdu);

        $result5 = $dbBroker->insert($kampanja);
//        if (isset($spotName)) {
//            $kampanjaID = $dbBroker->getLastInsertedId();
//            $spot = new Spot();
//            $spot->setKampanjaID($kampanjaID);
//            $spot->setSpotName($spotName);
//            $spot->setSpotLink($spotLink);
//            $spot->setTrajanje($trajanjeSpota);
//        }
        $test = $this->InsertBlok($allData, $dbBroker);
        //$result6 = $dbBroker->insert($spot);

        $responseNew = new CoreAjaxResponseInfo();
        if (!$result5 || !$test) {
            $error = true;
        }

        if (!$error) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana kampanja");
            return $responseNew;
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage('Trenutno ne mozete koristiti ovaj sablon jer su u medjuvremenu zauzete neke reklame.');
            return $responseNew;
        }
    }

    //Funkcija za punjenje klijenta za odredjenu agenciju
    public function KampanjaGetSpotList($kampanjaID, $start, $limit, $sort, $dir) {



        $kampanjaID+=0;

        $dbBroker = new CoreDBBroker();




        $query = "SELECT RadioStanicaID FROM kampanja WHERE KampanjaID=".$kampanjaID;
        $result = $dbBroker->selectOneRow($query);

        if ($result) {
            $radioStanicaID=0+$result['RadioStanicaID'];
        }else{
            die('error 35444');
        }



        $query = "select 
                    distinct
                    S.SpotID,
                    S.SpotName,
                    S.SpotTrajanje,
                    S.Ucestalost
                    from kampanjaspot as KS
                    inner join spot S on KS.SpotID = S.SpotID
        where KS.KampanjaID = $kampanjaID";


        $query = "select
                    distinct
                    S.SpotID,
                    S.SpotName,
                    S.SpotTrajanje,
                    S.Ucestalost,
					G.ImePrezime as Glas
                    from kampanjaspot as KS
                    inner join spot S on KS.SpotID = S.SpotID
					inner join glas G on S.GlasID = G.GlasID
        where KS.KampanjaID = $kampanjaID";



        if ($sort != '') {
            $querySort = " order by ";
            foreach($sort as $value){
                foreach($value as $value2) {
                    $querySort .= " ".$value2;
                }
            }
        } else {
            $querySort = " order by S.SpotID ";
        }
        $query .= $querySort;

        $result = $dbBroker->selectManyRows($query, $start, $limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {


                foreach($result['rows'] as $key => $row){
                    $result['rows'][$key]['RadioStanicaID']=$radioStanicaID;
                }


                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function GetSpotsForCampaigne($campaigneID = null, $tmpKampanjaClass = null) {
        //public function GetSpotsForCampaigne(TmpKampanjaClass $tmpKampanjaClass = null, $campaigneID = null) {
        if (is_null($campaigneID) || empty($campaigneID)) {
            $spotArray = $tmpKampanjaClass->getSpotArray();
            $spotIDs = "";
            foreach ($spotArray as $spot) {
                $spot instanceof Spot;
                $spotID = $spot->getSpotID();
                $spotIDs .= $spotID . ",";
            }
            $sportIDs = strlen($spotIDs) > 0 ? substr($spotIDs, 0, strlen($spotIDs) - 1) : "";
            $query = "select 
                    SpotID as EntryID,
                    SpotName as EntryName
                    from spot 
            where SpotID  in  ($sportIDs)";
        } else {
            $query = "select 
                    s.SpotID as EntryID,
                    s.SpotName as EntryName
                    from kampanjaspot ks 
                    inner join spot s on ks.SpotID = s.SpotID 
            where KampanjaID = $campaigneID";
        }
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:' . json_encode($result['rows']));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;
    }

    public function getDaysForCampaigne_old($campaigneId) {
        $days = array();
        $query = "select Datum from kampanjablok where KampanjaID = " . $campaigneId;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);

        foreach ($result['rows'] as $row) {
            if (!in_array($row['Datum'], $days)) {
                $days[] = $row['Datum'];
            }
        }

        return $days;
    }



    public function getDaysForCampaigne($campaigneId) {
        $campaigneId+=0;

        $query = "SELECT DatumPocetka,DatumKraja
            from kampanja k
            WHERE k.KampanjaID =" . $campaigneId;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);

        if(!$result){
            return array();
        }


        $startDate=date('Y-m-d', strtotime($result['DatumPocetka']));
        $endDate=date('Y-m-d', strtotime($result['DatumKraja']));

        /*
        $now = date('Y-m-d', time());
        if (strtotime($startDate) < strtotime($now)) {
            $startDate = $now;
        }

        if (strtotime($endDate) < strtotime($now)) {
            $daysForCampaign = array();
            return $daysForCampaign;
        }*/

        $daysForCampaign = array($startDate);
        $start = $startDate;
        $i = 1;
        if (strtotime($startDate) < strtotime($endDate)) {
            while (strtotime($start) < strtotime($endDate)) {
                $start = date('Y-m-d', strtotime($startDate . '+' . $i . ' days'));
                $daysForCampaign[] = $start;
                $i++;
            }
        }

        return $daysForCampaign;

    }




    public function getBlocksForCampaigne($campaigneId) {
        $blocks = array();
        $query = "select BlokID, Datum from kampanjablok where KampanjaID = " . $campaigneId;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);

        foreach ($result['rows'] as $row) {
            $blocks[$row['Datum']][] = $row['BlokID'];
        }

        return $blocks;
    }

    public function getCampaigneDetails($campaigneID) {
        $query = "SELECT kl.KlijentID, k.AgencijaID, k.SablonID, kl.DelatnostID, k.RadioStanicaID, k.CenaUkupno, k.Popust, k.Naziv
            from kampanja k 
            inner join klijent kl on k.KlijentID = kl.KlijentID
            WHERE k.KampanjaID =" . $campaigneID;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        return $result;
    }

    /*
    public function CalculateDiscount($klijentID = NULL, $agencijaID = NULL, $sablonID = NULL) {
        $popust = 0;
        //Ukoliko je kampanaj iz Sablona primerno Popust vucemo iz Sablona
        //Ukoliko je klijent dosao prkeo agencije ond aprimarno popust vucemo iz agencija
        //Ukoliko je klijent dosao direkktno onda popust gledmao na nivou klijenta
        $dbBroker = new CoreDBBroker();
        $query1 = "SELECT Popust FROM sablon WHERE SablonID = $sablonID";
        $result1 = $dbBroker->selectOneRow($query1);
        $query2 = "SELECT Popust FROM agencija WHERE AgencijaID = $agencijaID";
        $result2 = $dbBroker->selectOneRow($query2);
        if (!is_null($sablonID) && $result1['Popust'] > 0) {
            $popust = (int) $result1['Popust'];
        } elseif (!is_null($agencijaID) && $result2['Popust'] > 0) {
            $popust = (int) $result2['Popust'];
        } else {
            $query3 = "SELECT Popust FROM klijent WHERE KlijentID = $klijentID";
            $result3 = $dbBroker->selectOneRow($query3);
            $popust = (int) $result3['Popust'];
        }
        return $popust;
    }*/

    public function getPriceForBlock($blockID, $spotDuration, $date, $radioStationID, $discount, $pozicijaNova) {
        $testDay = date("N", strtotime($date));
        if (in_array($testDay, array(6, 7))) {
            $vikend = 1;
        } else {
            $vikend = 0;
        }
        $query1 = "select C.Cena
                                    from cenovnik C
                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID 
                                    where $spotDuration between KC.TrajanjeOd and KC.TrajanjeDo AND C.BlokID = $blockID AND C.RadioStanicaID = $radioStationID 
                                    and C.Vikend = $vikend";

        $dbBroker = new CoreDBBroker();
        $result1 = $dbBroker->selectOneRow($query1);
        //$price = number_format($result1['Cena'] - ($result1['Cena'] * $discount / 100), 2);
        $priceOfBlock = number_format(((100 - $discount) / 100) * $result1['Cena'], 2);

        $priceOfBlock=$spotDuration*CENA;
        $priceOfBlock = number_format(((100 - $discount) / 100) * $priceOfBlock, 2);
        if ($blockID % 2) {
            //Pretpostavk da u dugački blok mkogu da idu najviše 10 reklama
            //Ako su prve dve pozicije prazne(mora i druga kako ne bi jedan pored druge bile) mozes da ubacis u prvu poziciju
            if ($pozicijaNova == 1) {
                $priceOfBlock = (float) $priceOfBlock * 1;//1.2
            }
            if ($pozicijaNova == 2) {
                $priceOfBlock = (float) $priceOfBlock;
            }
            if ($pozicijaNova >= 3) {
                $priceOfBlock = (float) $priceOfBlock * 1;
            }
        } else {
            $priceOfBlock = (float) $priceOfBlock;
        }
        return $priceOfBlock;
    }

    public function GetSpotDetails($spotID) {
        $spotID+=0;
        $query = "select 
                    S.SpotID as spotID,
                    S.SpotLink as spotLink,
                    S.GlasID as glasID,
                    S.SpotTrajanje as trajanje
                    from spot as S
                    where S.spotID = " . $spotID;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        return $result;
    }


    public function GetSpotDetails2($spotID) {
        $spotID+=0;
        $query = "select
                    S.*
                    from spot as S
                    where S.spotID = " . $spotID;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        return $result;
    }


    
    public function AddAdditionalServices (KampanjaCenovnikUsluga $kampanjaCenovnikUsluga) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kampanjaCenovnikUsluga); 
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno unesena dodatna usluga");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
     public function GetAdditionalServices($kampanjaID, $start, $limit, $sort, $dir) {
        $query = "select 
                    CU.CenovnikUslugaID,
                    CU.Naziv,
                    CU.Cena
                    from kampanjacenovnikusluga as KCK
                    inner join cenovnikusluga CU on KCK.CenovnikUslugaID = CU.CenovnikUslugaID
        where KCK.KampanjaID = $kampanjaID";
        if ($sort != '') {
            $querySort = " order by $sort $dir";
        } else {
            $querySort = " order by CU.CenovnikUslugaID ";
        }
        $query .= $querySort;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $start, $limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }


































    public function KampanjaVratiSpotove($kampanja_id=0) {

        $kampanja_id+=0;



        $dbBroker = new CoreDBBroker();


        $query = "
        SELECT
        s.*
        FROM
        kampanjaspot ks
        LEFT JOIN spot s ON ks.SpotID = s.SpotID
        WHERE
        ks.KampanjaID =" . $kampanja_id ."
        ORDER BY KampanjaSpotID asc";



        $result = $dbBroker->selectManyRows($query);
        $spots = array();
        $k = 0;
        foreach ($result['rows'] as $row) {
            $k++;
            $row['rb']=$k;
            $spots[$k]=$row;




        }

        $dbBroker->close();
        return $spots;
    }




























































}

?>
