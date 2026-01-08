<?php
require_once __DIR__.'/../../init.php';

//include_once '../../App/Models/KampanjaZahtev.php';

if (isset($_GET['action'])) {
    $paraGet = $_GET['action'];
}
$parameter = $_POST['action'];
if (isset($paraGet)) {
    $parameter = $paraGet;
}

$kampanjaClass = new KampanjaClass();
$kampanja = new Kampanja();
//Indikator koji govori na koji način štampati kampanju
$getResponse = false;

switch ($parameter) {
    case "KampanjaLoad":
        $kampanjaID = $_POST['entryID'];
        $kampanja->setKampanjaID($kampanjaID);
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaPotvrdi":

        $predlog = $_POST['predlog'];
        if($predlog==1){
            $tmpKampanja = unserialize($_SESSION['tmpKampanja2']);
        }else{
            $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        }

        //$tmpKampanja = unserialize($_SESSION['tmpKampanja']);

        $response = $kampanjaClass->$parameter($tmpKampanja);
        break;
    case "KampanjaConfirm":
        $potvrda = $_FILES['dokument'];
        $file = new UploadedFileClass($potvrda);
        $fileInfo = new FileInfoClass($file, DokumentLokacije::PrilogIzjava);
        /*$spotFile = $_FILES['spot'];
        $file1 = new UploadedFileClass($spotFile);
        $fileInfo1 = new FileInfoClass($file1, DokumentLokacije::SpotDokument);*/
        if ($fileInfo->GetResponse()) {//&& $fileInfo1->GetResponse()


/*
            $spot = new Spot();
            $spot->setKampanjaID($_POST['kampanjaID']);
            $spot->setSpotLink($fileInfo1->Link);
            //$spot->setSpotName($fileInfo1->Name);
            $spotClass = new SpotClass();
            $spotClass->SpotUpdate($spot);*/



            $kampanja->setKampanjaID($_POST['kampanjaID']);
            $kampanja->setKomentarPotvrda($_POST['napomena']);
            $kampanja->setStatusKampanjaID(StatusKampanjaEnum::Potvrdjena);
            $kampanja->setVremePotvrde(date("Y-m-d H:i:s"));
            $kampanja->setKorisnikPotvrdaID($_SESSION['sess_idkor']);
            $kampanja->setPrilogIzjava($fileInfo->LinkFront);
            $parameter = "KampanjaUpdate";
            $response = $kampanjaClass->$parameter($kampanja);
        } else {
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess(FALSE);
            $response->SetMessage("Neuspešno kopiranje fajla!");
        }


        break;
    case "KampanjaZahtevInsert":
        $kampanjaZahtev = new KampanjaZahtev();

/*
        send_respons_boban($_POST);
exit;*/

        //$fieldValues = json_decode($fieldValues);
        //ovo je promenljiva koja govori koliko j espotova dodato
        $countSpot = $_POST['spotBroj'];


        $spotArray = array();
        for ($i = 1; $i <= $countSpot; $i++) {
            $spot = new Spot();
            $spotNeedle = 'spot' . $i;
            $dani = array();
            $periodi = array();
            $spotUcestalost = array();
            $daniStampa = "[";
            $periodiStampa = "[";
            //default mora biti 0
            $spot->setPremiumBlokovi(0);
            foreach ($_POST as $key => $value) {
                if (strpos($key, $spotNeedle) !== false) {
                    if (strpos($key, $spotNeedle . "dan") !== false && strlen($key) == 9) {
                        array_push($dani, $value);
                        $daniStampa .= $value;
                        $daniStampa .= ",";
                    }
//                    if (strpos($key, $spotNeedle . "period") !== false && strlen($key) == 12) {
//                        array_push($periodi, $value);
//                        $periodiStampa .= $value;
//                        $periodiStampa .= ",";
//                    }
                    if (strpos($key, $spotNeedle . "Ucestalost1") !== false && strlen($key) == 16 && !empty($value)) {
                        $spotUcestalost[1] = $value;
                        array_push($periodi, 1);
                        $periodiStampa .= 1;
                        $periodiStampa .= ",";
                    }
                    if (strpos($key, $spotNeedle . "Ucestalost2") !== false && strlen($key) == 16 && !empty($value)) {
                        $spotUcestalost[2] = $value;
                        array_push($periodi, 2);
                        $periodiStampa .= 2;
                        $periodiStampa .= ",";
                    }
                    if (strpos($key, $spotNeedle . "Ucestalost3") !== false && strlen($key) == 16 && !empty($value)) {
                        $spotUcestalost[3] = $value;
                        array_push($periodi, 3);
                        $periodiStampa .= 3;
                        $periodiStampa .= ",";
                    }
                    if (strpos($key, $spotNeedle . "Ucestalost4") !== false && strlen($key) == 16 && !empty($value)) {
                        $spotUcestalost[4] = $value;
                        array_push($periodi, 4);
                        $periodiStampa .= 4;
                        $periodiStampa .= ",";
                    }
                    if (strpos($key, $spotNeedle . "Ucestalost5") !== false && strlen($key) == 16 && !empty($value)) {
                        $spotUcestalost[5] = $value;
                        array_push($periodi, 5);
                        $periodiStampa .= 5;
                        $periodiStampa .= ",";
                    }
                    if (strpos($key, $spotNeedle . "premiumBlokovi") !== false && strlen($key) == 19 && !empty($value)) {
                        $spot->setPremiumBlokovi($value);
                    }
                    if (strpos($key, $spotNeedle . "naziv") !== false && strlen($key) == 10) {
                        $spot->setSpotName($value);
                    }
                    if (strpos($key, $spotNeedle . "glasID") !== false && strlen($key) == 11) {
                        $spot->setGlasID($value);
                    }
                    if (strpos($key, $spotNeedle . "trajanje") !== false && strlen($key) == 13) {
                        $spot->setSpotTrajanje($value);
                    }
                }
            }
            $daniStampa = substr($daniStampa, 0, -1);
            $periodiStampa = substr($periodiStampa, 0, -1);
            $daniStampa .= "]";
            $periodiStampa .= "]";
            $spot->setDaniZaEmitovanje($daniStampa);
            $spot->setPeriodiZaEmitovanje($periodiStampa);
            $spot->setDani($dani);
            $spot->setPeriodi($periodi);
            $spot->setSpotUcestalost($spotUcestalost);
            $spot->setUcestalostSuma();
            array_push($spotArray, $spot);
            //$spot = null;
        }






        $kampanjaZahtev->setRadioStanicaID($_POST['radioStanicaID']);
        $kampanjaZahtev->setBrendID($_POST['brendID']);
        $kampanjaZahtev->setSpotArray($spotArray);
        $kampanjaZahtev->setKlijentID($_POST['klijentID']);
        $kampanjaZahtev->setNaziv($_POST['naziv']);
        $kampanjaZahtev->setDatumPocetka($_POST['datumPocetka']);
        $kampanjaZahtev->setDatumKraja($_POST['datumKraja']);
        //$kampanjaZahtev->setUcestalost($_POST['ucestalost']);
        $kampanjaZahtev->setBudzet($_POST['budzet']);

        $kampanjaZahtev->setNapomena($_POST['napomena']);

        //$kampanjaZahtev->setSpotTrajanje($_POST['spotTrajanje']);
        //$kampanjaZahtev->setSpotName($_POST['spotName']);
        //$kampanjaZahtev->setDaniZaEmitovanje($daniStampa);
        //$kampanjaZahtev->setPeriodiDanaZaEmitovanje($periodiStampa);
        //$kampanjaZahtev->setDani($dani);
        //$kampanjaZahtev->setPeriodi($periodi);
        $kampanjaZahtev->setAgencijaID($_POST['agencijaID']);
        $kampanjaZahtev->setKorisnikID($_SESSION['sess_idkor']);


        $kampanjaZahtev->setTipPlacanjaID($_POST['tipPlacanjaID']);



//        if (isset($_POST['premiumBlokovi']) && $_POST['premiumBlokovi'] == 1) {
//            $kampanjaZahtev->setPremiumBlokovi($_POST['premiumBlokovi']);
//        } else {
//            $kampanjaZahtev->setPremiumBlokovi(0);
//        }

        if (isset($_POST['ponudaID']) && $_POST['ponudaID'] != -1) {
            $ponudaID = $_POST['ponudaID'];
        } else {
            $ponudaID = NULL;
        }



        $response12 = $kampanjaClass->$parameter($kampanjaZahtev, $ponudaID);


        $response1=$response12[0];
        $response2=$response12[1];//zanemaren budzet

        $response1=json_decode($response1->GetResponse());
        $response1->data->twoOffers=0;
        if($response2){//two offers
            $response2=json_decode($response2->GetResponse());
            $response1->data->twoOffers=1;
            $response1->data->capmaignePrice2=$response2->data->capmaignePrice;
            $response1->data->schedulerDates2=$response2->data->schedulerDates;
            $response1->data->schedulerCommercial2=$response2->data->schedulerCommercial;
        }

        $response = json_encode($response1);

        $getResponse=true;
        break;
    case "KampanjaManualCreate":
        //$spot = $_FILES['spot'];
        //Upload Spota
        //$file = new UploadedFileClass($spot);
        //$fileInfo = new FileInfoClass($file, DokumentLokacije::SpotDokument);


        $kampanjaZahtev = new KampanjaZahtev();
        //$fieldValues = json_decode($fieldValues);
        $kampanjaZahtev->setKlijentID($_POST['klijentID']);
        $kampanjaZahtev->setNaziv($_POST['naziv']);
        $kampanjaZahtev->setDatumPocetka($_POST['datumPocetka']);
        $kampanjaZahtev->setDatumKraja($_POST['datumKraja']);
        $kampanjaZahtev->setRadioStanicaID($_POST['radioStanicaID']);
        $kampanjaZahtev->setBrendID($_POST['brendID']);
        //$kampanjaZahtev->setBrendID(1);
        $kampanjaZahtev->setBudzet(0);

        $kampanjaZahtev->setNapomena($_POST['napomena']);

        //$kampanjaZahtev->setSpotTrajanje($_POST['spotTrajanje']);
        $kampanjaZahtev->setAgencijaID($_POST['agencijaID']);
        $kampanjaZahtev->setKorisnikID($_SESSION['sess_idkor']);


        $kampanjaZahtev->setTipPlacanjaID($_POST['tipPlacanjaID']);




        //Registrujemo podatke za spot
        $countSpot = $_POST['spotBroj'];


        $spotArray = array();
        for ($i = 1; $i <= $countSpot; $i++) {
            $spot = new Spot();
            $spotNeedle = 'spot' . $i;
            $dani = array();
            $periodi = array();
            $spotUcestalost = array();
            $daniStampa = "[";
            $periodiStampa = "[";
            //default mora biti 0
            $spot->setPremiumBlokovi(0);
            foreach ($_POST as $key => $value) {
                if (strpos($key, $spotNeedle) !== false) {

                    if (strpos($key, $spotNeedle . "naziv") !== false && strlen($key) == 10) {
                        $spot->setSpotName($value);
                    }
                    if (strpos($key, $spotNeedle . "glasID") !== false && strlen($key) == 11) {
                        $spot->setGlasID($value);
                    }
                    if (strpos($key, $spotNeedle . "trajanje") !== false && strlen($key) == 13) {
                        $spot->setSpotTrajanje($value);
                    }
                }
            }

            $spot->setKorisnikID($_SESSION['sess_idkor']);



            array_push($spotArray, $spot);
            //$spot = null;
        }


        $kampanjaZahtev->setSpotArray($spotArray);







//        if ($fileInfo->GetResponse()) {
//            $kampanjaZahtev->setSpotName($fileInfo->Name);
//            $kampanjaZahtev->setSpotLink($fileInfo->Link);
//        }
        $response = $kampanjaClass->$parameter($kampanjaZahtev);
        break;
    case "KampanjaInsert":
        $kampanja->setRadioStanicaID($_POST['radioStanicaID']);
        $kampanja->setKlijentID($_POST['klijentID']);
        $kampanja->setNaziv($_POST['naziv']);
        $kampanja->setDatumPocetka($_POST['datumPocetka']);
        $kampanja->setDatumKraja($_POST['datumKraja']);
        $kampanja->setUcestalost($_POST['ucestalost']);
        $kampanja->setAgencijaID($_POST['agencijaID']);
        $kampanja->setKorisnikID($_SESSION['sess_idkor']);
        $kampanja->setRedosledUBloku($_POST['redosledUBloku']);
        $kampanja->setStatusKampanjaID(StatusKampanjaEnum::Uneta);
        $kampanja->setBudzet($_POST['budzet']);
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaUpdate":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaStatusPromena":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanja->setKampanjaID($fieldValues->kampanjaID);
        $kampanja->setStatusKampanjaID($fieldValues->statusID);

        $napomena=$fieldValues->napomena;
        //$parameter = "KampanjaUpdate";
        $response = $kampanjaClass->$parameter($kampanja,$napomena);
        break;
    case "KampanjaPromeniFinansijskiStatus":


        if($korisnik_init['tipKorisnik']==3){
            $response = new CoreAjaxResponseInfo();
            $response->SetSuccess(FALSE);
            $response->SetMessage("Ova opcija Vam nije omogućena!");
            echo $response->GetResponse();
            exit;
        }


        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanja->setKampanjaID($fieldValues->kampanjaID);
        $kampanja->setFinansijskiStatusID($fieldValues->finansijskiStatusKampanjaID);
        $parameter = "KampanjaUpdate";
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaEdit":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $kampanja->setKampanjaID($fieldValues->kampanjaID);
        $kampanja->setDatumKraja($fieldValues->DatumKraja);
        $kampanja->setPopust((int)$fieldValues->popust);

        $kampanja->setTipPlacanjaID((int)$fieldValues->tipPlacanjaID);

        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaDelete":
        $kampanjaID = $_POST['entryID'];
        $kampanja->setKampanjaID($kampanjaID);
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaPregledEmitovanja":
        $kampanja->setKampanjaID($_POST['kampanjaID']);


        $kampanja->setRadioStanicaID($_POST['radioStanicaID']);

        $response = $kampanjaClass->$parameter($kampanja);
        $getResponse = true;
        break;
    case "KampanjaGetForComboBox":
        $response = $kampanjaClass->$parameter($kampanja);
        break;
    case "KampanjaPonudaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $ponudaID = $_POST['ponudaID'];
        $page = $_POST['page'];
        $filter = new FilterKampanjaPonuda($ponudaID, $start, $limit, $sort, $dir, $page);
        $response = $kampanjaClass->$parameter($filter);
        break;
    case "KampanjaGetList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        resolve_sort($sort,$dir);
        $page = $_POST['page'];
        $klijentID = $_POST['klijentID'];
        $filterValues = $_POST['filterValues'];
        $filterValues = json_decode($filterValues);

        if($korisnik_init['tipKorisnik']==3){
            $filterValues->agencijaListFilter=array((string)$korisnik_init['agencijaID']);
        }

        $filter = new FilterKampanja($filterValues, $start, $limit, $sort, $dir, $page);
        $filter->setKlijentID($klijentID);
        //$filter->setTipKorisnik($_SESSION['sess_tipkor']);
        $filter->setKorisnikID($_SESSION['sess_idkor']);


        $response = $kampanjaClass->$parameter($filter);
        break;
    case "KampanjaGetListForBlock":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        $page = $_POST['page'];
        $blok = $_POST['blok'];
        $datum = $_POST['datum'];

        $radioStanicaID = $_POST['RadioStanicaID'];
        //$radioStanicaID = 1;


        $filter = new FilterMediaPlanDetails($radioStanicaID, '', $blok, $datum, $start, $limit, $sort, $dir, $page);
        $response = $kampanjaClass->$parameter($filter);
        break;
    case "BlockGetDetailsForBlock":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        $page = $_POST['page'];
        $blockID = $_POST['blockID'];
        $blockDate = $_POST['blockDate'];

        //$campaigneID=$_POST['campaigneID'];

        $radioStanicaID = $_POST['campaigneID'];//$_POST['radioStanicaID'];
        //$radioStanicaID=1;



        $filter = new FilterMediaPlanDetails($radioStanicaID, '', $blockID, $blockDate, $start, $limit, $sort, $dir, $page);

        $response = $kampanjaClass->KampanjaGetListForBlock($filter);
        break;
    case "KampanjaValidateDND":
        $startBlockId = $_POST['startBlockId'];
        $dragStartDate = $_POST['dragStartDate'];
        $dragEndDate = $_POST['dragEndDate'];
        $endBlockId = $_POST['endBlockId'];
        $dropStartDate = $_POST['dropStartDate'];
        $dropEndDate = $_POST['dropEndDate'];
        $response = $kampanjaClass->$parameter($startBlockId, $dragStartDate, $dragEndDate, $endBlockId, $dropStartDate, $dropStartDate, $dropEndDate);
        break;
    case "BlokPremesti":
        /*
          $commercialBlockOrderID = $_POST['CommercialBlockOrderID'];
          $id = $_POST['Id'];
          $startD = $_POST['StartDate'];
          $endD = $_POST['EndDate'];
          $duration = $_POST['Duration'];
          $resourceId = $_POST['ResourceId'];
          var_dump('Id:'.$id.' | Start:'.$startD.' | End:'.$endD.' | Duration:'.$duration.' | Order:'.$commercialBlockOrderID.' | Resource:'.$resourceId);
          exit;
         */
        $response = $kampanjaClass->$parameter($id);
        //var_dump($response);
        exit;
        break;
    case "KampanjaValidateCREATE":
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $response = $kampanjaClass->$parameter($startDate, $endDate);
        break;
    case "KampanjaOnBeforeEventDelete":
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $commercialBlockOrderID = $_POST['commercialBlockOrderID'];
        $response = $kampanjaClass->$parameter($startDate, $endDate, $commercialBlockOrderID);
        break;
    case "PremestiBlok":
        //var_dump(unserialize($_SESSION['tmpKampanja']));
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $campaigneID = $fieldValues->campaigneID;
        $spotID = $fieldValues->spotID;
        $blokID = $fieldValues->blokID;
        $datumBloka = $fieldValues->datumBloka;
        $datumEmitovanja = $fieldValues->datumEmitovanja;
        $sat = $fieldValues->sat;
        $blok = $fieldValues->blok;
        $pozicija = $fieldValues->pozicija;

        $offersCount = $fieldValues->offersCount;
        $offerNo = $fieldValues->offerNo;


        if (isset($campaigneID) && !empty($campaigneID)) {
            $response = $kampanjaClass->PremestiBlokSecond($campaigneID, $spotID, $blokID, $datumBloka, $datumEmitovanja, $sat, $blok, $pozicija);
            $getResponse = true;
        } else {



            //$response = $kampanjaClass->PremestiBlokFirst($campaigneID, $spotID, $blokID, $datumBloka, $datumEmitovanja, $sat, $blok, $pozicija,$offerNo);
            //bilo staro




            $response = $kampanjaClass->PremestiBlokFirst($campaigneID, $spotID, $blokID, $datumBloka, $datumEmitovanja, $sat, $blok, $pozicija, $offersCount, $offerNo);


            if($offersCount==2){//dve ponude

                if(is_array($response)){//nije doslo do greske
                    $response1=$response[0];
                    $response2=$response[1];//zanemaren budzet

                    $response1=json_decode($response1->GetResponse());
                    $response2=json_decode($response2->GetResponse());

                    $response1->data->capmaignePrice2=$response2->data->capmaignePrice;
                    $response1->data->schedulerDates2=$response2->data->schedulerDates;
                    $response1->data->schedulerCommercial2=$response2->data->schedulerCommercial;
                    $response = json_encode($response1);
                    $getResponse=true;

                }else{
                    //greska (poruka) pri premesttanju bloka
                }
            }else{

            }



        }
        //var_dump(unserialize($_SESSION['tmpKampanja']));
        //var_dump($response);
        //exit;
        break;
    case "BlokObrisi":

        $campaigneID = $_POST['campaigneID'];
        $spotID = $_POST['spotID'];
        $blokID = $_POST['blokID'];
        $datumBloka = $_POST['datumBloka'];

        $offersCount = $_POST['offersCount'];
        $offerNo = $_POST['offerNo'];

        if (isset($campaigneID) && !empty($campaigneID)) {
            $response = $kampanjaClass->BlokObrisiSecond($campaigneID, $spotID, $blokID, $datumBloka);
            $getResponse = true;
        } else {


            //$response = $kampanjaClass->BlokObrisiFirst($campaigneID, $spotID, $blokID, $datumBloka);
            //staro


            $response = $kampanjaClass->BlokObrisiFirst($campaigneID, $spotID, $blokID, $datumBloka, $offersCount, $offerNo);
            $getResponse = true;


/*
            if($offersCount==2) {//dve ponude

                if (is_array($response)) {//nije doslo do greske
                    $response1 = $response[0];
                    $response2 = $response[1];//zanemaren budzet

                    $response1 = json_decode($response1->GetResponse());
                    $response2 = json_decode($response2->GetResponse());

                    $response1->data->capmaignePrice2 = $response2->data->capmaignePrice;
                    $response1->data->schedulerDates2 = $response2->data->schedulerDates;
                    $response1->data->schedulerCommercial2 = $response2->data->schedulerCommercial;
                    $response = json_encode($response1);
                    $getResponse = true;
                } else {
                    //$response = $response12;
                }

            }else{

            }*/



        }

        break;
    case "DodajBlok":
        $fieldValues = json_decode($_POST['fieldValues']);


        $campaigneID = $fieldValues->campaigneID;
        $spotID = $fieldValues->spotID;
        $datumEmitovanja = $fieldValues->datumEmitovanja;
        $blokID = $fieldValues->blokID;
        //$sat = 6+(int)((($blok-1) >= 0 ? ($blok -1 ) : 0)/4);

        $pozicija = $fieldValues->pozicija;

        if($blokID%2==0){
            $pozicija=1;
        }

        $offersCount = $fieldValues->offersCount;
        $offerNo = $fieldValues->offerNo;


        if (isset($campaigneID) && !empty($campaigneID)) {

/*
            send_respons_boban("DodajBlokSecond");
            return;*/

            $response = $kampanjaClass->DodajBlokSecond($campaigneID, $spotID, $datumEmitovanja, $blokID, $pozicija);
            $getResponse = true;
        } else {

            $response = $kampanjaClass->DodajBlokFirst($campaigneID, $spotID, $datumEmitovanja, $blokID, $pozicija, $offersCount, $offerNo);
            $getResponse = true;


            /*
            if($offersCount==2){//dva predloga

                if(is_array($response)){//nije doslo do greske
                    $response1=$response[0];
                    $response2=$response[1];//zanemaren budzet

                    $response1=json_decode($response1->GetResponse());
                    $response2=json_decode($response2->GetResponse());

                    $response1->data->capmaignePrice2=$response2->data->capmaignePrice;
                    $response1->data->schedulerDates2=$response2->data->schedulerDates;
                    $response1->data->schedulerCommercial2=$response2->data->schedulerCommercial;
                    $response = json_encode($response1);
                    $getResponse=true;

                }else{
                    //doslo do greske (poruke)
                }

            }else{

            }*/


        }

        break;
    case "DodajBlokNedeljniSablon":
        $fieldValues = $_POST['fieldValues'];
        $fieldValues = json_decode($fieldValues);
        $datumEmitovanja = $fieldValues->datumEmitovanja;
        $sat = $fieldValues->sat;
        $blok = $fieldValues->blok;
        $pozicija = $fieldValues->pozicija;
        $response = $kampanjaClass->$parameter($datumEmitovanja, $sat, $blok, $pozicija);
        break;
    case "KampanjaIzSablona":



        $kampanjaZahtev = new KampanjaZahtev();



        $sablonID = $_POST['sablonID'];
        $countSpot = $_POST['spotBroj'];
        $spotArray = array();
        for ($i = 1; $i <= $countSpot; $i++) {
            $spot = new Spot();
            $spotNeedle = 'spot' . $i;


/*
            $dani = array();
            $periodi = array();
            $spotUcestalost = array();
            $daniStampa = "[";
            $periodiStampa = "[";
*/


            //default mora biti 0
            $spot->setPremiumBlokovi(0);
            foreach ($_POST as $key => $value) {

/*
                if (strpos($key, "dan") !== false && strlen($key) == 4) {
                    array_push($dani, $value);
                    $daniStampa .= $value;
                    $daniStampa .= ",";
                }*/

                $spot->setPremiumBlokovi(1);//samo obicni u sablonu
/*
                if (strpos($key, "ucestalost") !== false && strlen($key) == 11 && !empty($value)) {

                    $period_pom=substr($key,10);
                    $spotUcestalost[$period_pom] = $value;
                    array_push($periodi, $period_pom);
                    $periodiStampa .= $period_pom;
                    $periodiStampa .= ",";


                }*/


                if (strpos($key, $spotNeedle) !== false) {

                    if (strpos($key, $spotNeedle . "naziv") !== false && strlen($key) == 10) {
                        $spot->setSpotName($value);
                    }
                    if (strpos($key, $spotNeedle . "glasID") !== false && strlen($key) == 11) {
                        $spot->setGlasID($value);
                    }
                    if (strpos($key, $spotNeedle . "trajanje") !== false && strlen($key) == 13) {
                        $spot->setSpotTrajanje($value);
                    }
                }
            }



/*
            $daniStampa = substr($daniStampa, 0, -1);
            $periodiStampa = substr($periodiStampa, 0, -1);
            $daniStampa .= "]";
            $periodiStampa .= "]";
*/


            //send_respons_boban($daniStampa);




/*
            $spot->setDaniZaEmitovanje($daniStampa);
            $spot->setPeriodiZaEmitovanje($periodiStampa);
            $spot->setDani($dani);
            $spot->setPeriodi($periodi);
            $spot->setSpotUcestalost($spotUcestalost);
            $spot->setUcestalostSuma();
*/


            array_push($spotArray, $spot);

        }



        $kampanjaZahtev->setRadioStanicaID($_POST['radioStanicaID']);
        $kampanjaZahtev->setBrendID($_POST['brendID']);
        $kampanjaZahtev->setSpotArray($spotArray);
        $kampanjaZahtev->setKlijentID($_POST['klijentID']);
        //$kampanjaZahtev->setNaziv($_POST['naziv']);
        $kampanjaZahtev->setDatumPocetka($_POST['datumPocetka']);
        $kampanjaZahtev->setDatumKraja($_POST['datumKraja']);
        //$kampanjaZahtev->setUcestalost($_POST['ucestalost']);
        $kampanjaZahtev->setBudzet(0);

        $kampanjaZahtev->setNapomena($_POST['napomena']);


        $kampanjaZahtev->setAgencijaID($_POST['agencijaID']);
        $kampanjaZahtev->setKorisnikID($_SESSION['sess_idkor']);


        $kampanjaZahtev->setTipPlacanjaID($_POST['tipPlacanjaID']);





        if (isset($_POST['ponudaID']) && $_POST['ponudaID'] != -1) {
            $ponudaID = $_POST['ponudaID'];
        } else {
            $ponudaID = NULL;
        }


        $response = $kampanjaClass->$parameter($kampanjaZahtev, $ponudaID, $sablonID);
        break;
    case "KampanjaGetSpotList":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = json_decode($_POST['sort'],true);
        //$sort = $_POST['sort'];
        $dir = $_POST['dir'];
        $page = $_POST['page'];
        $kampanjaID = $_POST['kampanjaID'];
        $response = $kampanjaClass->$parameter($kampanjaID, $start, $limit, $sort, $dir);
        break;
    case "GetSpotsForCampaigne":
        $campaigneID = $_POST['campaigneID'];
        $tmpKampanja = unserialize($_SESSION['tmpKampanja']);
        //$response = $kampanjaClass->$parameter($tmpKampanja, $campaigneID);
        $response = $kampanjaClass->$parameter($campaigneID, $tmpKampanja);
        break;
    case "AddAdditionalServices":
        $fieldValues = json_decode($_POST['fieldValues']);
        $kampanjaID = $_POST['kampanjaID'];
        $cenovnikUslugaID = $_POST['cenovnikUslugaID'];
        $kampanjaCenovnikUsluga = new KampanjaCenovnikUsluga();
        $kampanjaCenovnikUsluga->setKampanjaID($fieldValues->kampanjaID);
        $kampanjaCenovnikUsluga->setCenovnikUslugaID($fieldValues->cenovnikUslugaID);
        $response = $kampanjaClass->$parameter($kampanjaCenovnikUsluga);

    case "GetAdditionalServices":
        $start = $_POST['start'];
        $limit = $_POST['limit'];
        $sort = $_POST['sort'];
        $dir = $_POST['dir'];
        $page = $_POST['page'];
        $kampanjaID = $_POST['kampanjaID'];
        $response = $kampanjaClass->$parameter($kampanjaID, $start, $limit, $sort, $dir);
    default:
        break;
}
ob_clean();
if ($getResponse) {
    echo $response;
} else {
    echo $response->GetResponse();
}
ob_flush();
?>