<?php


function move_element_of_array_by_key($arr,$key,$x=0){//x +/- elemenata
    if(!isset($arr[$key])){
        return $arr;
    }
    $value=null;
    $i=1;
    foreach($arr as $key_pom => $value_pom ){
        if($key_pom==$key){
            $value=$value_pom;
            unset($arr[$key_pom]);
            break;
        }
        $i++;
    }

    $arr_new=array();
    $j=1;
    $TF=false;
    foreach($arr as $key2 => $value2 ){
        if($j==($i+$x)){
            $TF=true;
            $arr_new[$key]=$value;
        }
        $arr_new[$key2] = $value2;
        $j++;
    }
    if(!$TF){
        if($x<0){
            $arr_new=array($key=>$value) + $arr_new;
        }else{
            $arr_new[$key]=$value;
        }
    }
    return $arr_new;
}


class TmpKampanjaClass {

    //Ovde odmah na početku dodajme property koji će čuvati niz spotova s anjihovim karakteristikama
    //Kao
    private $spotArray = array();
    private $budzetArray = array();
    private $tempSpotArray = array();
    private $kampanjaId;
    private $klijentId;
    private $agencijaId;
    private $delatnostId;
    private $brendID;
    private $campaignePrice = 0;
    private $radioStanicaId;
    private $naziv;
    private $pocetak;
    private $kraj;
    private $budzet;
    //private $ucestalost;
    private $trajanjeSpota;
    //private $dobaDana;
    //private $zeljeniDani;
    private $ponudaId;
    //private $nedeljniSablon;
    private $sablonId;


    private $tipPlacanjaId;




    private $zauzetiBlokoviZaPrikaz = array();
    //private $izabraniBlokoviZaPrikaz;
    //private $trenutnaCena;
    //private $sortiraniDostupniZeljeniDani;
    //private $ukupnoZauzetoBlokova;
    //private $maksimalanBrojBlokova;
    //private $pomocniNiz;
    private $popust = 0;
    //private $izabraniBlokovi = array();
    private $positions = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30);

    private $hours = array();



    public $predjen_budzet=0;//ako je prilikom kreiranja kampanja osakacena zbog budzeta (ovde je setovano zanemari_budzet=false)


    public $allDays=array();



    //private $premiumBlokovi = 1;
    //private $sortiraniBlokoviSaCenama;
    //private $control = 0;
    //private $finish = 0;
    //private $colors = array(1 => '#B0171F', 2 => '#1C86EE', 3 => '#00FF7F', 4 => '#FFFF00', 5 => '#CD8500', 6 => '#8B7D6B', 7 => '#CD3700', 8 => '#848484', 9 => '#575757');

    public function __construct($klijentId, $agencijaId, $radioStanicaId, $brendID, $naziv, $pocetak, $kraj, $budzet,  $napomena, $spotArray, $ponudaId = NULL, $sablonId = NULL, $tipPlacanjaId = 0 ) {

        // ulazni parametar zeljeni dani treba da bude niz sa vrednostima od 1 do 7 u zavisnosti koje dane zelimo 1 je ponedeljak a 7 je nedelja
        $this->radioStanicaId = $radioStanicaId;

        $this->brendID = $brendID;
        $this->klijentId = $klijentId;
        //$this->delatnostId = 1;
        $this->delatnostId = $this->GetDelatnost();//uzima iz brenda pa klijenta

        $this->agencijaId = $agencijaId;
        $this->naziv = $naziv;
        $this->pocetak = $pocetak;
        $this->kraj = $kraj;
        $this->budzet = $budzet;

        $this->napomena = $napomena;

        $this->spotArray = $spotArray;
        //$trajanjeNajdzegSpota = $this->GetTrajanjeNajduzegSpota($spotArray);
        //$ucestalost = 3;
        //$this->ucestalost = $ucestalost;
        //$this->trajanjeSpota = $trajanjeSpota;
        //$this->spotNaziv = $spotNaziv;
        //$this->dobaDana = $dobaDana;
        //$this->zeljeniDani = $zeljeniDani;
        //$this->premiumBlokovi = $premiumBlokovi;
        $this->ponudaId = $ponudaId;
        $this->sablonId = $sablonId;

        $this->tipPlacanjaId = $tipPlacanjaId;

        //$this->nedeljniSablon = $nedeljniSablon;
        //$this->delatnostId = $this->GetDelatnostId();

        $this->popust = $this->CalculateDiscount();

        //$this->popust =0;




//        $sortiraniDostupniZeljeniDani = $this->GetAvailableDaysForCampaign($pocetak, $kraj, $spotArray);
//        // ovde formiramo jedan pomocni niz sa svim onim podacima koji su do sad bili na nivo kampanje a sad su na nivou spota
//        $tempSpotArray = array();
//        foreach ($this->spotArray as $spot) {
//            $spot instanceof Spot;
//            $spotID = $spot->getSpotID();
//            $tempSpotArray[$spotID]['finish'] = 0;
//            $tempSpotArray[$spotID]['control'] = 0;
//            $tempSpotArray[$spotID]['spotTrajanje'] = $spot->getSpotTrajanje();
//            $tempSpotArray[$spotID]['spotName'] = $spot->getSpotName();
//            $tempSpotArray[$spotID]['spotID'] = $spotID;
//            $tempSpotArray[$spotID]['glasID'] = $spot->getGlasID();
//            $tempSpotArray[$spotID]['sortiraniDostupniZeljeniDani'] = $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
//            $tempSpotArray[$spotID]['sortiraniBlokoviSaCenama'] = $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
//            $tempSpotArray[$spotID]['numberDays'] = $spot->getNumberDays();
//            $tempSpotArray[$spotID]['days'] = $spot->getDays();
//            $tempSpotArray[$spotID]['maksimalanBrojBlokova'] = $tempSpotArray[$spot->getSpotID()]['numberDays'] * $spot->getUcestalotSuma();
//            $tempSpotArray[$spotID]['pomocniNiz'] = $this->setPomocniNiz($sortiraniDostupniZeljeniDani[$spot->getSpotID()], $spot->getSpotUcestalost());
//            $tempSpotArray[$spotID]['trenutnaCena'] = 0;
//            $tempSpotArray[$spotID]['ukupnoZauzetoBlokova'] = 0;
//            $tempSpotArray[$spotID]['izabraniBlokovi'] = array();
//            $tempSpotArray[$spotID]['zauzetiBlokoviZaPrikaz'] = array();
//            $tempSpotArray[$spotID]['izabraniBlokoviZaPrikaz'] = array();
//        }
//        $this->tempSpotArray = $tempSpotArray;

        $this->calculateBuget();//popunjava $this->budzetArray(spot_ids)=budzet za taj spot


        $this->populateHours();


        $this->allDays = $this->GetAllDays($pocetak, $kraj);




    }

    public function getSpotArray() {
        return $this->spotArray;
    }

    public function setSpotArray($spotArray) {
        $this->spotArray = $spotArray;
    }

    public function getTempSpotArray() {
        return $this->tempSpotArray;
    }

    public function setKampanjaID($kampanjaID) {
        $this->kampanjaId = $kampanjaID;
    }

    public function getKampanjaID() {
        return $this->kampanjaId;
    }

    public function setKlijentID($klijentID) {
        $this->klijentId = $klijentID;
    }

    public function getKlijentID() {
        return $this->klijentId;
    }



    public function setSablonID($sablonId) {
        $this->sablonId = $sablonId;
    }

    public function getSablonID() {
        return $this->sablonId;
    }


    public function setPopust($popust) {
        $this->popust = $popust;
    }

    public function getPopust() {
        return $this->popust;
    }




    public function getNaziv() {
        return $this->naziv;
    }

    public function getRadioStanicaID() {
        return $this->radioStanicaId;
    }

    public function getDelatnostID() {
        return $this->delatnostId;
    }

    public function getBrendID() {
        return $this->brendID;
    }

    public function getStartOfCampaigne() {
        return $this->pocetak;
    }

    public function getEndOfCampaigne() {
        return $this->kraj;
    }

    public function getCampaignePrice() {
        return $this->campaignePrice;
    }

    public function setCampaignePrice($campaignePrice) {
        $this->campaignePrice = $campaignePrice;
    }

    public function getBudzet() {
        return $this->budzet;
    }





    public function setNapomena($napomena) {
        $this->napomena = $napomena;
    }

    public function getNapomena() {
        return $this->napomena;
    }





    public function getPonudaID() {
        return $this->ponudaId;
    }


    public function getTipPlacanjaID() {
        return $this->tipPlacanjaId;
    }









    public function getSpotByID($spotID) {
        return $this->tempSpotArray[$spotID];
    }

    public function setSpotByID($spotID, $tempSpot) {
        $this->tempSpotArray[$spotID] = $tempSpot;
    }




    public function getTmpKampanja($zanemari_budzet=0, $ponavljanje_spota_u_satu=0)
    {




        $debug_my = 0;


        $ponavljanje_spota_u_satu=(bool)$ponavljanje_spota_u_satu;

        $zanemari_budzet = (bool)$zanemari_budzet;


        if(isset($this->sablonId)){
            $zanemari_budzet=(bool)1;
        }




        //file_put_contents('spotArray.txt', print_r($this->spotArray, true));
        //file_put_contents('tempSpotArray.txt', print_r($this->tempSpotArray, true));
        $zauzetiBlokovi = array();
        $tempSpotArray = array();


        $spot_rb=0;
        foreach ($this->spotArray as $spot) {
            $spot_rb+=1;
            //$spot instanceof Spot;


            $this->zauzetiBlokoviZaPrikaz=array();//dodao
            $sortiraniDostupniZeljeniDani = $this->GetAvailableDaysForCampaign($this->pocetak, $this->kraj, $spot);


/*
            var_dump($sortiraniDostupniZeljeniDani);
exit;

            send_respons_boban($sortiraniDostupniZeljeniDani);
            exit;
*/




            //optimizacija blokovi sa istim cenama biraju se prvo neparni sati

/*
            for($i=2;$i<=5;$i++ ){
                if(isset($sortiraniDostupniZeljeniDani[$spot->getSpotID()][$i])) {
                    $sortiraniDostupniZeljeniDani[$spot->getSpotID()][$i] = $this->makeBestOrder($sortiraniDostupniZeljeniDani[$spot->getSpotID()][$i]);
                }
            }

            var_dump($sortiraniDostupniZeljeniDani);
            exit;
*/

            //$sortiraniDostupniZeljeniDani[$spotId][$period][datum][$block_id . '/redosled_u_bloku'] = $price; (sortiran po ceni ASC) (ovo sve samo za odredjeni spot !!!)
            //nema praznih nizova ?!?!?!?!? (OK)
            //UZETO U OBZIR GLAS I DELATNOST (function GetPriceForBlocks() )

            //usput setovano
            //$spot->setNumberDays; //broj avalible days (NIJE UZET U OBZIR GLAS I DELATNOST - MOZDA JE IZBACIO BLOKOVE ZBOG GLAS ILI DELATNOST PA IH UOPSTE NEMA ZA NEKI DAN)
            //$spot->setDays;//array(avalible days) (NIJE UZET U OBZIR GLAS I DELATNOST - -II- )
            //ubacuje datume u blokZauzece ako nisu



            //$this->zauzetiBlokoviZaPrikaz - $this->zauzetiBlokoviZaPrikaz[days][block_ids] (svi zauzeti blokovi po trajanju i po glas delatnost nezavisno od vrste)
            //ovde nisu popunjeni blokovi od prethodnih spotova itd STO TAKO I TREBA!!!!

            //************************************************************************


/*
                        var_dump($sortiraniDostupniZeljeniDani);
                        exit;*/






            $spotID = $spot->getSpotID();
            $tempSpot = array();



            $tempSpot['rb'] = $spot_rb;

            $tempSpot['finish'] = 0;
            $tempSpot['control'] = 0;
            $tempSpot['spotTrajanje'] = $spot->getSpotTrajanje();
            $tempSpot['spotName'] = $spot->getSpotName();
            $tempSpot['spotID'] = $spotID;
            $tempSpot['glasID'] = $spot->getGlasID();

            $tempSpot['premiumBlokovi'] = $spot->getPremiumBlokovi();

            //$tempSpot['sortiraniDostupniZeljeniDani'] = $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
            //$tempSpot['sortiraniBlokoviSaCenama'] =     $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
            $tempSpot['numberDays'] = $spot->getNumberDays();
            $tempSpot['days'] = $spot->getDays();
            $tempSpot['maksimalanBrojBlokova'] = $tempSpot['numberDays'] * $spot->getUcestalotSuma(); //block/position










/*
            var_dump($tempSpot['pomocniNiz']);
            exit;*/


            $tempSpot['trenutnaCena'] = 0;
            $tempSpot['ukupnoZauzetoBlokova'] = 0;//  block/position
            $tempSpot['izabraniBlokovi'] = array();
            //$this->zauzetiBlokoviZaPrikaz Čuva odgovarajuce blokove za svaku iteraciju spota


            $tempSpot['zauzetiBlokoviZaPrikaz'] = $this->zauzetiBlokoviZaPrikaz;


            $tempSpot['izabraniBlokoviZaPrikaz'] = array();



            $periodi = $spot->getPeriodi();
            sort($periodi);

            $tempSpot['sortiraniBlokoviSaCenama'] =     $sortiraniDostupniZeljeniDani[$spot->getSpotID()];
            $tempSpot['sortiraniDostupniZeljeniDani'] = $sortiraniDostupniZeljeniDani[$spot->getSpotID()];









            /**********************************************************************************************************
             * IZBACUJE IZABRANI block/position iz $tempSpot['sortiraniDostupniZeljeniDani']
             * ovo radi i za novi sledeci spot jer je stari zauzeo block/position-s
             * a $sortiraniDostupniZeljeniDani za svaki spot vraca iz tabele blokZauzece koja se u algoritmu nemenja
             *******************/
            if (count($zauzetiBlokovi) > 0) {
                foreach ($zauzetiBlokovi as $day => $blokoviArray) {
                    foreach ($blokoviArray as $zauzetBlok) {
                        foreach ($this->positions as $p) {
                            $unsetKey = $zauzetBlok . '/' . $p;
                            foreach ($periodi as $period) {
                                if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$unsetKey])) {
                                    unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$unsetKey]);
                                }
                            }
                        }
                    }
                }
            }
            /******************************************************************************************************/


            $tempSpot['pomocniNiz'] = $this->setPomocniNiz($tempSpot['sortiraniDostupniZeljeniDani'], $spot->getSpotUcestalost(), $ponavljanje_spota_u_satu);
            //$tempSpot['pomocniNiz'][period][datum]= array('izabranoBlokovaUDanu' => 0, 'minimalnaCena' => $min, 'maksimalnaCena' => $max, 'srednjaCena' => $middle, 'periodDanUcestalost' => $periodDanUcestalost);
            //$periodDanUcestalost= izabran broj emitovanja za taj period i dan








            $this->tempSpotArray[$spotID] = $tempSpot;

            $ucestalosti = $spot->getSpotUcestalost();





            while ($tempSpot['finish'] == 0) {


                /**********************************************************************************************************
                 * IZBACUJE IZABRANI block/position iz $tempSpot['sortiraniDostupniZeljeniDani']
                 * ovo radi i za novi sledeci spot jer je stari zauzeo block/position-s
                 * a $sortiraniDostupniZeljeniDani za svaki spot vraca iz tabele blokZauzece koja se u algoritmu nemenja
                 *******************/

                /*
                if (count($zauzetiBlokovi) > 0) {
                    foreach ($zauzetiBlokovi as $day => $blokoviArray) {
                        foreach ($blokoviArray as $zauzetBlok) {
                            foreach ($this->positions as $p) {
                                $unsetKey = $zauzetBlok . '/' . $p;
                                foreach ($periodi as $period) {
                                    if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$unsetKey])) {
                                        unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$unsetKey]);
                                    }
                                }
                            }
                        }
                    }
                }*/

                /******************************************************************************************************/




/*
                var_dump($tempSpot['sortiraniDostupniZeljeniDani']);
                exit;
*/




                //Promenljiva koja nam čuva koliko je stvarno zauzeto blokova u trenutnom periodu u tom danu
                //*$periodTrenutnoZauzeto = 0;
                foreach ($periodi as $period) {



                    foreach ($tempSpot['sortiraniDostupniZeljeniDani'][$period] as $day => $blocks) {
                        //ide kroz period/dan


                        // 2. korak proverava da li su izabrani svi blokovi u tom danu, ako jesu idemo na sledeci dan
                        //Dodajemo proveru $spot->getUcestalotSuma() == 0 zbog manuelne kampanje
                        //*$periodTrenutnoZauzeto = $ucestalosti[$period] - $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'];
                        if ($tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] < $tempSpot['pomocniNiz'][$period][$day]['periodDanUcestalost'] ) {//|| $spot->getUcestalotSuma() == 0
                            //u izabranoBlokovaUDanu manje nego u periodDanUcestanost (obe ove vrednosti su u $tempSpot['pomocniNiz'])

                            // 3. proveravamo da li je preostali budzet veci od minimalne cene bloka u tom danu
                            $TF_budzet=(($this->budzetArray[$spotID] - $tempSpot['trenutnaCena']) >= $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']);
                            if (  $TF_budzet ||  ($zanemari_budzet)  ) {



                                $test = ($this->budzetArray[$spotID] - $tempSpot['trenutnaCena']) / ($tempSpot['maksimalanBrojBlokova'] - $tempSpot['ukupnoZauzetoBlokova']);
                                //$test (srednja cena da se svi blokovi popune do kraja za taj spot)

                                // 4. korak

                                if($debug_my){
                                    echo " xxx period: ".$period."/".$day." test: ".$test." - ".$tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']."/".$tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'];
                                    foreach ($blocks as $key => $value) {
                                        echo " * ".$key."=>".$value." * ";

                                    }
                                }



                                if ($test >= $tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena']) {

                                    //$blocks=array_reverse($blocks); (ovo doraditi ako treba sortiranje po ceni, izbacio kad sam napravio sortiranje po ceni pa po slobodnom mestu u bloku, posto je cena sada ista po ceni i nesortira)

                                    foreach ($blocks as $key => $value) {//$key= $block_id . '/redosled_u_bloku'              $value=cena

                                        $tempSpot['izabraniBlokovi'][$day][$key] = $value;
                                        $blockId = substr($key, 0, strpos($key, '/'));



                                        if($ponavljanje_spota_u_satu) {


                                            $zauzetiBlokovi[$day][] = $blockId;
                                            foreach ($this->positions as $position) {
                                                $keyForUnset = $blockId . '/' . $position;
                                                if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                    unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                }
                                            }

                                        }else{

                                            $zauzetiBlokovi[$day][] = $blockId;
                                            $blockId_arr = $this->VratiBlokoveUIstomSatu($blockId,1);
                                            foreach ($blockId_arr as $blockId_pom) {
                                                //$zauzetiBlokovi[$day][] = $blockId_pom; pomeren gore zbog drugog spota
                                                foreach ($this->positions as $position) {
                                                    $keyForUnset = $blockId_pom . '/' . $position;
                                                    if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                        unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                    }
                                                }
                                            }

                                        }



                                        $tempSpot['trenutnaCena'] = $tempSpot['trenutnaCena'] + $value;
                                        $tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] = max($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                        $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena'] = min($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                        $tempSpot['pomocniNiz']['srednjaCena'] = ($tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] + $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']) / 2;
                                        $tempSpot['ukupnoZauzetoBlokova'] = $tempSpot['ukupnoZauzetoBlokova'] + 1;
                                        $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] = $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] + 1;
                                        $tempSpot['control'] = 1;
                                        if($debug_my) {
                                            echo " cena1:" . $key . "=>" . $value;
                                        }

                                        break;

                                    }
                                    //continue;
                                } else if($test <= $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']) {
                                    foreach ($blocks as $key => $value) {
                                        $tempSpot['izabraniBlokovi'][$day][$key] = $value;
                                        $blockId = substr($key, 0, strpos($key, '/'));

                                        if($ponavljanje_spota_u_satu) {

                                            $zauzetiBlokovi[$day][] = $blockId;
                                            foreach ($this->positions as $position) {
                                                $keyForUnset = $blockId . '/' . $position;
                                                if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                    unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                }
                                            }

                                        }else{
                                            $zauzetiBlokovi[$day][] = $blockId;
                                            $blockId_arr = $this->VratiBlokoveUIstomSatu($blockId,1);
                                            foreach ($blockId_arr as $blockId_pom) {
                                                //$zauzetiBlokovi[$day][] = $blockId_pom;
                                                foreach ($this->positions as $position) {
                                                    $keyForUnset = $blockId_pom . '/' . $position;
                                                    if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                        unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                    }
                                                }
                                            }

                                        }

                                        $tempSpot['trenutnaCena'] = $tempSpot['trenutnaCena'] + $value;
                                        $tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] = max($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                        $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena'] = min($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                        $tempSpot['pomocniNiz'][$period][$day]['srednjaCena'] = ($tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] + $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']) / 2;
                                        $tempSpot['ukupnoZauzetoBlokova'] = $tempSpot['ukupnoZauzetoBlokova'] + 1;
                                        $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] = $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] + 1;
                                        $tempSpot['control'] = 1;
                                        if($debug_my) {
                                            echo " cena2:" . $key . "=>" . $value;
                                        }
                                        break;
                                    }
                                        //continue;



                                      /*
                                    } else {

                                        // 6. korak
                                        $partArray = array();
                                        foreach ($blocks as $key => $value) {
                                            if ($value < $tempSpot['pomocniNiz'][$period][$day]['srednjaCena']) {
                                                $partArray[$key] = $value;
                                            }
                                        }

                                        $maxValue = max($partArray);
                                        foreach ($partArray as $key => $value) {
                                            if ($value == $maxValue) {
                                                $tempSpot['izabraniBlokovi'][$day][$key] = $value;
                                                $blockId = substr($key, 0, strpos($key, '/'));
                                                $zauzetiBlokovi[$day][] = $blockId;
                                                foreach ($this->positions as $position) {
                                                    $keyForUnset = $blockId . '/' . $position;
                                                    if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                        unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                    }
                                                }

                                                $tempSpot['trenutnaCena'] = $tempSpot['trenutnaCena'] + $value;
                                                $tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] = max($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                                $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena'] = min($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                                $tempSpot['pomocniNiz'][$period][$day]['srednjaCena'] = ($tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] + $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']) / 2;
                                                $tempSpot['ukupnoZauzetoBlokova'] = $tempSpot['ukupnoZauzetoBlokova'] + 1;
                                                $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] = $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] + 1;
                                                $tempSpot['control'] = 1;
                                                break;
                                            }
                                        }
                                        //continue;
                                    }*/




                                }else{//izmedju min i max

                                    //$blocks=array_reverse($blocks); (ovo doraditi ako treba sortiranje po ceni, izbacio kad sam napravio sortiranje po ceni pa po slobodnom mestu u bloku, posto je cena sada ista po ceni i nesortira)

                                    foreach ($blocks as $key => $value) {//$key= $block_id . '/redosled_u_bloku'              $value=cena
                                        if ($test >= $value) {
                                            $tempSpot['izabraniBlokovi'][$day][$key] = $value;
                                            $blockId = substr($key, 0, strpos($key, '/'));

                                            if($ponavljanje_spota_u_satu) {

                                                $zauzetiBlokovi[$day][] = $blockId;
                                                foreach ($this->positions as $position) {
                                                    $keyForUnset = $blockId . '/' . $position;
                                                    if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                        unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                    }
                                                }

                                            }else{
                                                $zauzetiBlokovi[$day][] = $blockId;
                                                $blockId_arr = $this->VratiBlokoveUIstomSatu($blockId,1);
                                                foreach ($blockId_arr as $blockId_pom) {
                                                    //$zauzetiBlokovi[$day][] = $blockId_pom;
                                                    foreach ($this->positions as $position) {
                                                        $keyForUnset = $blockId_pom . '/' . $position;
                                                        if (isset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset])) {
                                                            unset($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day][$keyForUnset]);
                                                        }
                                                    }
                                                }

                                            }

                                            $tempSpot['trenutnaCena'] = $tempSpot['trenutnaCena'] + $value;
                                            $tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] = max($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                            $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena'] = min($tempSpot['sortiraniDostupniZeljeniDani'][$period][$day]);
                                            $tempSpot['pomocniNiz']['srednjaCena'] = ($tempSpot['pomocniNiz'][$period][$day]['maksimalnaCena'] + $tempSpot['pomocniNiz'][$period][$day]['minimalnaCena']) / 2;
                                            $tempSpot['ukupnoZauzetoBlokova'] = $tempSpot['ukupnoZauzetoBlokova'] + 1;
                                            $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] = $tempSpot['pomocniNiz'][$period][$day]['izabranoBlokovaUDanu'] + 1;
                                            $tempSpot['control'] = 1;
                                            if($debug_my) {
                                                echo " cena3:" . $key . "=>" . $value;
                                            }
                                            break;
                                        }
                                    }

                                }
                            }else{//predjen budzet
                                $this->predjen_budzet=1;
                            }
                        }
                    }
                }


/*
                if ($tempSpot['trenutnaCena'] == 0) {
                    $tempSpot['finish'] = 1;
                }*/

                // 8. korak pocinje ovde
                if ($tempSpot['control'] != 0) {
                    $tempSpot['control'] = 0;
                    // idi na pocetak
                } else {
                    $tempSpot['finish'] = 1;
                    // ide na 9. korak
                }


            }//while ($tempSpot['finish'] == 0) { (glavna petlja posle spotova)



            //exit;


            /*
              if($this->ukupnoZauzetoBlokova == $this->maksimalanBrojBlokova){
              $_SESSION['porukaPreporuke'] = 'Kako bi Vasa kampanja imala sto vise efekta za ovaj budzet, molimo Vas da povecate ucestalost reklame ili broj dana za kampanju.';
              }
             */
            $tempSpot['izabraniBlokoviZaPrikaz'] = $this->vratiIzabraneBlokoveZaPrikaz($tempSpot['izabraniBlokovi']);

            //$tempSpot['izabraniBlokoviZaPrikaz']=      array[days]=array[block_ids]




            //Ovde cenu spota konacnu sabiramno kako bi dobili ukupnu cenu kampanje
            $this->campaignePrice += $tempSpot['trenutnaCena'];
            $this->tempSpotArray[$spotID] = $tempSpot;
        }



        return $this;
    }



    public function GetAvailableDaysForCampaign($startDate, $endDate, $spot) {
        $sortBlocksByPrice = array();
        $delatnostId = $this->delatnostId;
        //$delatnostId = $this->GetDelatnostId();
        //$this->calculateBuget();
        //$tmp = $this->budzetArray;
        $spotId = $spot->getSpotID();
        $glasId = $spot->getGlasID();
        $trajanjeSpota = $spot->getSpotTrajanje();
        $premiumBlokovi = $spot->getPremiumBlokovi();
        //$messages = array();
        //$spot instanceof  Spot;


        $daysForCampaign = $this->GetDaysForCampaign($startDate, $endDate, $spot);
        //$daysForCampaign = array(datumi kampanje); (vraca samo dane setovane u kampanji znaci oduzima nesetovane dane u nedelji)
        //(i setuje)
        //$spot->setNumberDays; //broj dana
        //$spot->setDays;//ovaj niz
        //ubacuje datume u blok zauzece ako nisu
        //************************************************************************




        if (!empty($daysForCampaign)) {
            $periodi = $spot->getPeriodi();

/*
            send_respons_boban($periodi);
                        exit;*/


            $daysWithFreeBlocks = $this->GetFreeBlocksForDays($daysForCampaign, $trajanjeSpota, $periodi, $premiumBlokovi, $glasId, $delatnostId);




/*
            var_dump($daysWithFreeBlocks);
            exit;*/


            /*
            send_respons_boban($daysWithFreeBlocks);
                        exit;
            */

            //DODAO SAM DA SU BLOKOVI SORTIRANI PO KEY=PREOSTALO SEKUNDI DESC (znaci dodao sam key(preostalo sekundi)=>blok_id)

            //OVO AUTOMATSKI DOVODI DO TOGA DA SE $priceForBlocks ISPOD DOBIJA TAKODJE PO ISTOM SORTU (REOSTALO SEKUNDI DESC)


            //$daysWithFreeBlocks[period][datum]=array(block_ids) (slobodni blokovi za taj spot za $daysForCampaign(sve dane kampanje) )
            //(periodi koji nepripadaju spotu su prazni nizovi: $daysWithFreeBlocks[period][datum]=array() )
            //(ovo je uradjeno na osnovu trajanja spota iz BlokZauzece tabele i da li su premijum ili obicni iz kampanje )
            // i PLUS BLOKOVI KOJI NEPOSTOJE AKO JE VIKEND (nisu u ovom nizu!!!!!)
            //(NEUZIMA SE U OBZIR GLAS I DELATNOST !!!)

            // takodje se popunjava
            // $this->zauzetiBlokoviZaPrikaz[datum]=array(blok_ids) (svi zauzeti blokovi samo po trajanju i ako je popunjen premijum (nezavisno od vrste bloka))
            // i PLUS BLOKOVI KOJI NEPOSTOJE AKo JE VIKEND!!!
            //************************************************************************

            foreach ($periodi as $value) {
                if (!empty($daysWithFreeBlocks[$value])) {
                    $discount = $this->popust;



                    //var_dump($daysWithFreeBlocks[$value]);



                    $priceForBlocks = $this->GetPriceForBlocks($this->radioStanicaId, $daysWithFreeBlocks[$value], $trajanjeSpota, $discount, $glasId, $delatnostId);
                    //sortirano po preostalo sekundi DESC

                    //var_dump('po preostalo sekundi');
                    //var_dump($priceForBlocks);




/*
                    foreach($priceForBlocks["2016-05-25"] as $key5 =>$val5){
                        $rand=rand(10,30);
                        echo $key5.'---'.$val5.'<br>';
                        $priceForBlocks["2016-05-25"][$key5]=$rand;
                    }


                    var_dump($priceForBlocks);
*/


                    //$priceForBlocks[datum][$block_id . '/redosled_u_bloku'] = $price; - ovde su svi blokovi i pozicije koji su dostupni za spot po svim kriterijumima (trajanje, delatnost, glas, tip bloka(premijum ili obican ili oba), cena i delatnost nesme biti 0)
                    //ovo se sve odnosi na trenutan period koji je u petlji!!


                    //ovde su takodje u niz $this->zauzetiBlokoviZaPrikaz[$datum][blok]=poruka, dodati blokovi koji otpadaju zbog glasa ili delatnosti
                    //takodje i blokovi ako je cena 0 sto nesme da se desi!!!
                    //takodje ako je delatnost nedefinisana onda su svi blokovi u ovom nizu, a $priceForBloks je prazan
                    //************************************************************************

/*
                    var_dump($value);
                    var_dump($priceForBlocks);
*/



                    //var_dump('po ceni');

                    $pom=$this->SortBlocksByPrice($priceForBlocks);

                    //var_dump($pom);

                    if($value!=1){
                        $pom= $this->makeBestOrder($pom);
                    }

/*
                    var_dump('best order');
                    var_dump($pom);
                    exit;
*/


                    $sortBlocksByPrice[$spotId][$value] = $pom;
                    //$sortBlocksByPrice[$spotId][$period][datum][$block_id . '/redosled_u_bloku'] = $price; (sortiran po ceni ASC)
                    //************************************************************************



                    //$sortBlocksByPrice = $this->SortBlocksByPrice($priceForBlocks);
                    //var_dump($sortBlocksByPrice); exit;
                    //return $sortBlocksByPrice;
                } else {
                    //$messages['message'] = 'U izabranom periodu nema slobodnih termina za Vasu kampanju.';
                    //return $messages;
                }

            }
            //exit;
        } else {
            //$messages['message'] = 'Niste izabrali ni jedan datum za Vasu kampanju.';
            //return $messages;
        }
        //$sortBlocksByPrice[1122] = $sortBlocksByPrice[$spotId];



        return $sortBlocksByPrice;
    }


    public function GetFreeBlocksForDays($daysForCampaign, $spotDuration, $dobaDana, $premiumBlokovi, $glasId, $delatnostId) {



        $freeBlocksForDays = array();
        foreach ($daysForCampaign as $availableDay) {

            $zauzetiBlokoviZaPrikaz = $this->vratiZauzeteBlokove($this->radioStanicaId, $availableDay, $spotDuration);

            /*
                        send_respons_boban($zauzetiBlokoviZaPrikaz);
                        exit;
            */

            //$zauzetiBlokoviZaPrikaz - svi blokovi on 1 do 72 u koje nemoze da stane spot zbog duzine trajanja ili je popunjen premijum (za odredjenu radiostanicu i datum) !!!!!!!!!!!!!!!!!!!!!
            $this->zauzetiBlokoviZaPrikaz[$availableDay] = $zauzetiBlokoviZaPrikaz;


            switch (true) {
                case ($premiumBlokovi == 1):
                    $query = "select BlokID, Sat
                           from blok as B
                           where BlokID in (
                                select blok.BlokID
                                from blokzauzece
                                join blok
                                on blokzauzece.BlokID = blok.BlokID and blokzauzece.RadioStanicaID = '$this->radioStanicaId'
                                where (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi >= $spotDuration and blok.Vrsta = 0)) ";

                    break;
                case ($premiumBlokovi == 2):
                    $query = "select BlokID, Sat
                           from blok as B
                           where BlokID in (
                                select blok.BlokID
                                from blokzauzece
                                join blok
                                on blokzauzece.BlokID = blok.BlokID and blokzauzece.RadioStanicaID = '$this->radioStanicaId'
                                where (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi >= $spotDuration and blok.Vrsta = 1 ))";//and blokzauzece.ZauzetaPrva = 0

                    break;
                case ($premiumBlokovi == 3):
                    $query = "select BlokID, Sat
                           from blok as B
                           where BlokID in (
                                select blok.BlokID
                                from blokzauzece
                                join blok
                                on blokzauzece.BlokID = blok.BlokID and blokzauzece.RadioStanicaID = '$this->radioStanicaId'
                                where (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi >= $spotDuration and blok.Vrsta = 0)
                                or (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi >= $spotDuration and blok.Vrsta = 1 and blokzauzece.ZauzetaPrva = 0)) ";
                    break;
            }


            if (count($dobaDana) != 5) {
                if (count($dobaDana) == 0) {
                    echo 'Nije izabrano doba dana';
                    exit;
                } else {
                    $query .= " and (1=0 ";
                    if (in_array(1, $dobaDana)) {
                        $query .= " or (B.Sat between 6 and 7)";
                    }
                    if (in_array(2, $dobaDana)) {
                        $query .= " or (B.Sat between 8 and 11)";
                    }
                    if (in_array(3, $dobaDana)) {
                        $query .= " or (B.Sat between 12 and 16)";
                    }
                    if (in_array(4, $dobaDana)) {
                        $query .= " or (B.Sat between 17 and 19)";
                    }
                    if (in_array(5, $dobaDana)) {
                        $query .= " or (B.Sat between 20 and 24)";
                    }
                    $query .= " )";
                }
            }






            $dbBroker = new CoreDBBroker();
            $rows = $dbBroker->selectManyRows($query);



            //$blocks = array();
            $freeBlocksForDays[1][$availableDay] = array();
            $freeBlocksForDays[2][$availableDay] = array();
            $freeBlocksForDays[3][$availableDay] = array();
            $freeBlocksForDays[4][$availableDay] = array();
            $freeBlocksForDays[5][$availableDay] = array();
            foreach ($rows['rows'] as $row) {



                if(array_key_exists($row['BlokID'], $zauzetiBlokoviZaPrikaz)){//zbog nepostojecih blokova vikendom od 6 i 23
                    continue;
                }



                $blok_id_pom=0+$row['BlokID'];
                $sql="SELECT PreostaloSekundi FROM blokzauzece WHERE BlokID=".$blok_id_pom." AND RadioStanicaID = ".$this->radioStanicaId." AND Datum = '".$availableDay."'";

                $result_pom = $dbBroker->selectOneRow($sql);
                $preostalo_sekundi = 0+$result_pom['PreostaloSekundi'];


                $sat = $row['Sat'];
                switch (1) {
                    case $sat >= 6 && $sat <= 7:
                        $freeBlocksForDays[1][$availableDay][$preostalo_sekundi]=$row['BlokID'];
                        //array_push($freeBlocksForDays[1][$availableDay], $row['BlokID']);
                        break;
                    case $sat >= 8 && $sat <= 11:
                        $freeBlocksForDays[2][$availableDay][$preostalo_sekundi]=$row['BlokID'];
                        //array_push($freeBlocksForDays[2][$availableDay], $row['BlokID']);
                        break;
                    case $sat >= 12 && $sat <= 16:
                        $freeBlocksForDays[3][$availableDay][$preostalo_sekundi]=$row['BlokID'];
                        //array_push($freeBlocksForDays[3][$availableDay], $row['BlokID']);
                        break;
                    case $sat >= 17 && $sat <= 19:
                        $freeBlocksForDays[4][$availableDay][$preostalo_sekundi]=$row['BlokID'];
                        //array_push($freeBlocksForDays[4][$availableDay], $row['BlokID']);
                        break;
                    case $sat >= 20 && $sat <= 24:
                        $freeBlocksForDays[5][$availableDay][$preostalo_sekundi]=$row['BlokID'];
                        //array_push($freeBlocksForDays[5][$availableDay], $row['BlokID']);
                        break;
                }
                //$blocks[] = $row['BlokID'];
            }

            //if (!empty($blocks)) {
            //  $freeBlocksForDays[$availableDay] = $blocks;
            //}



            /* sortiranje po preostalo sekundi desc*/
            krsort($freeBlocksForDays[1][$availableDay]);
            krsort($freeBlocksForDays[2][$availableDay]);
            krsort($freeBlocksForDays[3][$availableDay]);
            krsort($freeBlocksForDays[4][$availableDay]);
            krsort($freeBlocksForDays[5][$availableDay]);




        }

        /*
                var_dump($freeBlocksForDays);
                exit;*/





        return $freeBlocksForDays;
    }


    public function GetPriceForBlocks($radioStanicaId, $freeBlocksForDays, $spotDuration, $discount, $glasId, $delatnostId) {//ovo se vec odnosi na odredjeni period ($freeBlocksForDays-za neki period gore je u petlji perioda)
        $priceForBlocks = array();
        //$preostaloForBlocks = array();


        //$vikend_nepostojeci_blokovi=array(1,2,3,4,69,70,71,72);





        foreach ($freeBlocksForDays as $key => $value) {//key je datum


            //ovde vrtimo period/day

            $blocks = array();//cene
            //$blocks2 = array();//preostalo sekundi

            foreach ($value as $block) { //$value=array(block_ids)

                //$preostalo_sekundi+=0;
                //ovde vrtimo blokove za odredjeni period/day (ALI SAMO ZA SETOVANU VRSTU BLOKA !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!)


                // ovde treba da ide upit koji vraca jedan red iz tabele cenovnik na osnovu $block (to je BlokID) i $kategorijaCenaId
                /*
                  $query1 = "select C.Cena
                  from cenovnik C
                  inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID
                  where $spotDuration between KC.TrajanjeOd
                  and KC.TrajanjeDo AND C.BlokID = $block
                  and ((weekday($key) in (5,6) and C.Vikend = 1) or (weekday($key) not  in (5,6) and C.Vikend = 0))";
                 */

                $testDay = date("N", strtotime($key));
                if (in_array($testDay, array(6, 7))) {
                    $vikend = 1;
                } else {
                    $vikend = 0;
                }


/*OVO JE VEC URADJEN NEVIDIM ZASTO JE PONOVO
                if($vikend==1 && in_array($block,$vikend_nepostojeci_blokovi)){
                    $this->zauzetiBlokoviZaPrikaz[$key][$block] = 'Vikendom u 6h i 23h se neemituju reklame !';
                    continue;
                }
*/





//                $query1 = "select C.Cena
//                                    from cenovnik C
//                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID
//                                    where $spotDuration between KC.TrajanjeOd and KC.TrajanjeDo AND C.BlokID = $block AND C.RadioStanicaID = $radioStanicaId
//                                    and C.Vikend = $vikend";
                $query1 = "select C.Cena
                                    from cenovnik C
                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID
                                    where $spotDuration >= KC.TrajanjeOd and  $spotDuration <= KC.TrajanjeDo AND C.BlokID = $block AND C.RadioStanicaID = $radioStanicaId
                                    and C.Vikend = $vikend";


                $dbBroker = new CoreDBBroker();
                $result1 = $dbBroker->selectOneRow($query1);
                $price = number_format($result1['Cena'] - ($result1['Cena'] * $discount / 100), 2);




                $tipPlacanjaID=$this->getTipPlacanjaID();
                if($tipPlacanjaID==8){//politicki marketing
                    $price = (float) $price * 2;
                }


                if($price==0) {
                    $this->zauzetiBlokoviZaPrikaz[$key][$block] = "UPOZORENJE - Cena bloka je 0 din !!!";
                    continue;
                }


                if(!isset($delatnostId)){
                    $this->zauzetiBlokoviZaPrikaz[$key][$block] = "UPOZORENJE - Nije setovana delatnost za klijenta !!!";
                    continue;
                }





                //cena za odredjeni block sa popustom (ne za block/position)
                //*********************************************************


                //$query2 = "select BZ.ZauzetaPrva, BZ.ZauzetaDruga
                //from blokzauzece BZ
                //where BZ.BlokID = $block AND BZ.Datum = '" . $key . "'";
                //$result2 = $dbBroker->selectOneRow($query2);
                //Dohvatis sve Redoslede iz emitovanja koji za taj Datum i BlokID imaju ili GlasID ili DealtnostID istu kao ulaz
                $query3 = "select KB.Redosled, KB.GlasID, KB.DelatnostID
                                    from kampanjablok KB
                                    where KB.BlokID = $block AND KB.RadioStanicaID = $radioStanicaId AND KB.Datum = '" . $key . "'";
                $result3 = $dbBroker->selectManyRows($query3);
                $redosledArray = array();
                $glasArray = array();
                $delatnostArray = array();
                foreach ($result3['rows'] as $row) {
                    array_push($redosledArray, $row['Redosled']);
                    $glasArray[$row['Redosled']] = $row['GlasID'];
                    $delatnostArray[$row['Redosled']] = $row['DelatnostID'];
                }
                $redosledMax = empty($redosledArray) || max($redosledArray) < 3 ? 3 : max($redosledArray) + 1;


                /*
                                var_dump($redosledArray);
                                var_dump($glasArray);
                                var_dump($delatnostArray);
                                var_dump($redosledMax);
                */
                /*
                var_dump( (!in_array(1, $redosledArray)) );
                var_dump( ( $glasArray[2] != $glasId ) );
                var_dump( ( $delatnostArray[2] != $delatnostId ) );
                var_dump( (  $price != 0) );
*/






                if ($block % 2) {//obican blok (ne premium)
                    //Posatvljamo flag da ako se ne izabere ni jedna pozicija d aj eproglasi zauzetom zbog Glasa i Delatnosti


                    //$TF_1=false;
                    //$TF_2=false;
                    //$TF_3=false;
                    //$blockIsUnavailable = true;

                    $blockIsAvailable_glas_1 = false;
                    $blockIsAvailable_glas_2 = false;
                    $blockIsAvailable_glas_3 = false;

                    //Pretpostavk da u dugački blok mkogu da idu najviše 10 reklama
                    //Ako su prve dve pozicije prazne(mora i druga kako ne bi jedan pored druge bile) mozes da ubacis u prvu poziciju
                    if (!in_array(1, $redosledArray) && $glasArray[2] != $glasId) {//&& $price != 0 - vec gore zbog cene nestize ovde
                        //$blocks[$block . '/1'] = (float) $price * 1.2;
                        //$TF_1=true;
                        $blockIsAvailable_glas_1 = true;
                    }
                    if (!in_array(2, $redosledArray) && $glasArray[1] != $glasId  && $glasArray[3] != $glasId ) {// && $price != 0
                        //$blocks[$block . '/2'] = (float) $price;
                        //$TF_2=true;
                        $blockIsAvailable_glas_2 = true;
                    }
                    if (!in_array($redosledMax, $redosledArray) && $glasArray[$redosledMax - 1] != $glasId) {// && $price != 0
                        //$blocks[$block . '/' . $redosledMax] = (float) $price * 0.8;
                        //$TF_3=true;
                        $blockIsAvailable_glas_3 = true;

                    }



                    $blockIsAvailable_delatnost_1 = false;
                    $blockIsAvailable_delatnost_2 = false;
                    $blockIsAvailable_delatnost_3 = false;

                    if (!in_array(1, $redosledArray) && $delatnostArray[2] != $delatnostId) {//&& $price != 0 - vec gore zbog cene nestize ovde
                        //$blocks[$block . '/1'] = (float) $price * 1.2;
                        //$TF_1=true;
                        $blockIsAvailable_delatnost_1 = true;
                    }
                    if (!in_array(2, $redosledArray) && $delatnostArray[1] != $delatnostId && $delatnostArray[3] != $delatnostId) {// && $price != 0
                        //$blocks[$block . '/2'] = (float) $price;
                        //$TF_2=true;
                        $blockIsAvailable_delatnost_2 = true;
                    }
                    if (!in_array($redosledMax, $redosledArray) && $delatnostArray[$redosledMax - 1] != $delatnostId) {// && $price != 0
                        //$blocks[$block . '/' . $redosledMax] = (float) $price * 0.8;
                        //$TF_3=true;
                        $blockIsAvailable_delatnost_3 = true;
                    }


                    $blockIsUnavailable=true;
                    if($blockIsAvailable_glas_1 && $blockIsAvailable_delatnost_1){
                        //$blocks[$block . '/1'] = (float) $price * 1.2;
                        //$blockIsUnavailable=false;
                    }
                    if($blockIsAvailable_glas_2 && $blockIsAvailable_delatnost_2){
                        $blocks[$block . '/2'] = (float) $price;
                        $blockIsUnavailable=false;
                    }
                    if($blockIsAvailable_glas_3 && $blockIsAvailable_delatnost_3){
                        $blocks[$block . '/' . $redosledMax] = (float) $price * 1;
                        $blockIsUnavailable=false;
                    }

                    //$blocks2[$block]=$preostalo_sekundi;

                    //$blockIsUnavailable=$blockIsUnavailable_glas || $blockIsUnavailable_delatnost;


                    //proverimo da li je nedostupan


                    if ($blockIsUnavailable) {

                        //array_push($this->zauzetiBlokoviZaPrikaz[$key], $block);



                        $msg='glas ili delatnost se poklapaju !';
                        if( $blockIsAvailable_glas_2 || $blockIsAvailable_glas_3){//$blockIsAvailable_glas_1 ||
                        }else{
                            $msg='glas se poklapa !';
                        }
                        if( $blockIsAvailable_delatnost_2 || $blockIsAvailable_delatnost_3){//$blockIsAvailable_delatnost_1 ||
                        }else{
                            $msg='delatnost se poklapa !';
                        }
                        if( $blockIsAvailable_glas_2 || $blockIsAvailable_glas_3 ||  $blockIsAvailable_delatnost_2 || $blockIsAvailable_delatnost_3){//$blockIsAvailable_glas_1 || $blockIsAvailable_delatnost_1 ||
                        }else{
                            $msg='glas i delatnost se poklapaju !';
                        }

                        $this->zauzetiBlokoviZaPrikaz[$key][$block] = $msg;
                        continue;
                    }

                } else {
                    $blocks[$block . '/1'] = (float) $price;
                    //$blocks2[$block]=$preostalo_sekundi;
                }



            }
            $priceForBlocks[$key] = $blocks;
            //$preostaloForBlocks[$key] = $blocks2;
        }

        return $priceForBlocks;
    }

    public function vratiZauzeteBlokove($radioStanicaId, $availableDay, $spotDuration) {
        $zauzetiBlokoviZaPrikaz = array();


        //Ovo je query za one u koj ene može da stane ta dužina spota
        /*
        $query = "select BlokID, Sat
                   from blok as B
                   where BlokID in (
                        select blok.BlokID
                        from blokzauzece
                        join blok
                        on blokzauzece.BlokID = blok.BlokID and blokzauzece.RadioStanicaID = '$radioStanicaId'
                        where ((blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi < $spotDuration) or (blokzauzece.Datum = '$availableDay' and blok.Vrsta = 1 and blokzauzece.ZauzetaPrva = 1))) ";
*/
        $query = "select bz.BlokID
                        from blokzauzece bz
                        left join blok b
                        on bz.BlokID = b.BlokID and bz.RadioStanicaID = '$radioStanicaId'
                        where ((bz.Datum = '$availableDay' and bz.PreostaloSekundi < $spotDuration) or (bz.Datum = '$availableDay' and b.Vrsta = 1 and bz.ZauzetaPrva = 1))
                        order by bz.BlokID ";


        $dbBroker = new CoreDBBroker();
        $rows = $dbBroker->selectManyRows($query);
        foreach ($rows['rows'] as $row) {
            //$zauzetiBlokoviZaPrikaz[] = $row['BlokID'];
            $zauzetiBlokoviZaPrikaz[$row['BlokID']] = 'nema mesta u bloku !!!';

        }


        $testDay = date("N", strtotime($availableDay));
        if (in_array($testDay, array(6, 7))) {
            $vikend = 1;
        } else {
            $vikend = 0;
        }

        if($vikend){

            $zauzetiBlokoviZaPrikaz[1] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[2] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[3] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[4] = "Vikendom u 6h i 23h se neemituju reklame !";

            $zauzetiBlokoviZaPrikaz[69] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[70] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[71] = "Vikendom u 6h i 23h se neemituju reklame !";
            $zauzetiBlokoviZaPrikaz[72] = "Vikendom u 6h i 23h se neemituju reklame !";
        }



        /*
                var_dump($zauzetiBlokoviZaPrikaz);
                exit;*/


        return $zauzetiBlokoviZaPrikaz;
    }













































    private function calculateBuget() {
        $spotBuget = array();
        $tempA = array();
        $tempSum = 0;
        foreach ($this->getSpotArray() as $spot) {
            //$spot instanceof Spot; 
            $tempA[$spot->getSpotID()] = $spot->getUcestalotSuma() * $spot->getSpotTrajanje();
            $tempSum += $spot->getUcestalotSuma() * $spot->getSpotTrajanje();
        }
        foreach ($this->getSpotArray() as $spot) {
            //$spot instanceof Spot; 
            $spotBuget[$spot->getSpotID()] = ($tempA[$spot->getSpotID()] / $tempSum) * $this->budzet;
        }
        $this->budzetArray = $spotBuget;
    }

    private function setPomocniNiz($sortiraniDostupniZeljeniDani, $spotUcestalost, $ponavljanje_spota_u_satu=0) {//za odredjeni spot
        //$ostatak = array();
        //$keyBefore = 0;

        $ostatak =array();//odvojeno po danima

        //$sortiraniDostupniZeljeniDani[$period][datum][$block_id . '/redosled_u_bloku'] = $price; (za odredjeni spot)

        foreach ($sortiraniDostupniZeljeniDani as $key => $value) { //$key=period





            foreach ($value as $keyDate => $valuePrice) { // $valuePrice[$block_id . '/redosled_u_bloku'] = $price;




                $min = min($valuePrice);
                $max = max($valuePrice);
                $middle = ($min + $max) / 2;




                //$brojSlobodnihUperiodu = count($valuePrice);//broj slobodnih blok/redosled u periodu





                //var_dump($valuePrice);

                $slobodni_blokovi=array();
                foreach ($valuePrice as $blok => $cena) {
                    $niz = explode('/', $blok);
                    $blokID = $niz[0];
                    //var_dump($blokID);
                    $blocks=array();
                    if($ponavljanje_spota_u_satu) {
                        $blocks[] = $blokID;
                    }else{
                        $blocks = $this->vratiBlokoveUIstomSatu($blokID,1);
                    }
                    //var_dump($blocks);
                    $TF=false;
                    foreach ($blocks as $blokid_pom) {
                        if (in_array($blokid_pom, $slobodni_blokovi)) {
                            $TF=true;
                            break;
                        }
                    }
                    if(!$TF) {
                        $slobodni_blokovi[] = $blokID;
                    }

                }
/*
                var_dump($slobodni_blokovi);
                exit;*/
                $brojSlobodnihUperiodu = count($slobodni_blokovi);






                if($brojSlobodnihUperiodu >= $spotUcestalost[$key]){
                    $periodDanUcestalost = $spotUcestalost[$key];
                    if(isset($ostatak[$keyDate])) {
                        if ($ostatak[$keyDate] > 0) {
                            $ostatak_pom = $ostatak[$keyDate];
                            for ($i = 1; $i <= $ostatak_pom; $i++) {
                                if ($brojSlobodnihUperiodu > $periodDanUcestalost) {
                                    $periodDanUcestalost += 1;
                                    $ostatak[$keyDate] -= 1;
                                }
                            }
                        }
                    }
                    //$periodDanUcestalost = $spotUcestalost[$key] + $ostatak[$keyBefore][$keyDate];
                }else{
                    $periodDanUcestalost = $brojSlobodnihUperiodu;
                    $ostatak[$keyDate]=$spotUcestalost[$key]-$brojSlobodnihUperiodu;
                }

                //$periodDanUcestalost = $brojSlobodnihUperiodu >= $spotUcestalost[$key] ? $spotUcestalost[$key] + $ostatak[$keyBefore][$keyDate] : $brojSlobodnihUperiodu;//GRESKAAAAAAA sta ako $spotUcestalost[$key] + $ostatak[$keyBefore][$keyDate] budu veci od $brojSlobodnihUperiodu ?!?!?!?!?!!?!?


                //$periodDanUcestalost (broj izabranih emitovanja(blok/redosled) za period) (moze biti manji od zadatih ako nema mesta) (a moze biti i veci GRESKAAAAAA gore)

                //$ostatak[$key][$keyDate] = $spotUcestalost[$key] - $periodDanUcestalost;
                //$ostatak[period][datum] (ako je ostalo spotova koji nemogu da se emituju u periodu)

                $pomocniNiz[$key][$keyDate] = array('izabranoBlokovaUDanu' => 0, 'minimalnaCena' => $min, 'maksimalnaCena' => $max, 'srednjaCena' => $middle, 'periodDanUcestalost' => $periodDanUcestalost);
                //Ovde setujemo kolika je ucestanost za taj period i konkretan dan    
            }
            //$keyBefore = $key;
        }

        return $pomocniNiz;
    }

    public function CalculateDiscount() {
        $popust = 0;
        //Ukoliko je kampanaj iz Sablona primerno Popust vucemo iz Sablona
        //Ukoliko je klijent dosao prkeo agencije ond aprimarno popust vucemo iz agencija
        //Ukoliko je klijent dosao direkktno onda popust gledmao na nivou klijenta
        $dbBroker = new CoreDBBroker();

        if(isset($this->sablonId) && $popust==0) {
            $query1 = "SELECT Popust FROM sablon where SablonID = $this->sablonId";
            $result1 = $dbBroker->selectOneRow($query1);
            $popust = (int) $result1['Popust'];
        }

        if(isset($this->agencijaId) && $popust==0){
            $query2 = "SELECT Popust FROM agencija WHERE AgencijaID = $this->agencijaId";
            $result2 = $dbBroker->selectOneRow($query2);
            $popust = (int) $result2['Popust'];
        }

        if(isset($this->klijentId) && $popust==0){
            $query3 = "SELECT Popust FROM klijent WHERE KlijentID = $this->klijentId";
            $result3 = $dbBroker->selectOneRow($query3);
            $popust = (int) $result3['Popust'];
        }

        return $popust;
    }






    public function GetDaysForCampaign($startDate, $endDate, Spot $spot) {
        $now = date('Y-m-d', time());
        $includedDays = $spot->getDani();
        if (strtotime($startDate) < strtotime($now)) {
            $startDate = $now;
        }

        if (strtotime($endDate) < strtotime($now)) {
            $daysForCampaign = array();
            return $daysForCampaign;
        }

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

        if (!empty($includedDays)) {
            $newDaysForCampaign = array();
            foreach ($daysForCampaign as $date) {
                $day = date("N", strtotime($date));
                if (in_array($day, $includedDays)) {
                    $newDaysForCampaign[] = $date;
                }
            }
            $this->InsertDaysInProcess($newDaysForCampaign);
            $spot->setNumberDays(count($newDaysForCampaign));
            $spot->setDays($newDaysForCampaign);
            return $newDaysForCampaign;
        } else {
            return array();
        }
    }

    public function GetAllDays($startDate, $endDate) {
        $now = date('Y-m-d', time());

        if (strtotime($startDate) < strtotime($now)) {
            $startDate = $now;
        }

        if (strtotime($endDate) < strtotime($now)) {
            $daysForCampaign = array();
            return $daysForCampaign;
        }

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


    public function SortBlocksByPrice($priceForBlocks) {
        $priceForBlocks_sorted=array();

        foreach ($priceForBlocks as $datum => $arr) {//key == datum (arr je niz arr[block_id/poz]=cena)

            $priceForBlocks_sorted[$datum]=array();
            //za ovo setujemo po ceni ali da se nepromeni redosled onih sa istom cenom (znaci ako je cena svuda ista, nesme da promeni redosled) jer je vec sortiran po preostalo sekundi
            $arr_sorted=array();


            while(true) {

                if(count($arr)==0){
                    break;
                }


                $cena_min = 0;
                foreach ($arr as $blockid_poz => $cena) {
                    if ($cena_min == 0) {
                        $cena_min = $cena;
                    }
                    if ($cena < $cena_min) {
                        $cena_min = $cena;
                    }
                }

                foreach ($arr as $blockid_poz => $cena) {
                    if ($cena == $cena_min) {
                        $arr_sorted[$blockid_poz] = $cena;
                        unset($arr[$blockid_poz]);
                    }
                }
/*
                foreach ($arr as $blockid_poz => $cena) {
                    if ($cena == $cena_min) {
                        //$arr_sorted[$blockid_poz] = $cena;
                        unset($arr[$blockid_poz]);
                    }
                }*/


            }

            $priceForBlocks_sorted[$datum]=$arr_sorted;

        }
        return $priceForBlocks_sorted;
    }

    public function SortBlocksByPrice_old($priceForBlocks) {
        foreach ($priceForBlocks as $key => $value) {
            uasort($value, function($a, $b) {
                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? -1 : 1;
            });
            $sortBlocksByPrice[$key] = $value;
        }
        return $sortBlocksByPrice;
    }

    public function InsertDaysInProcess($days) {
        $dbBroker = new CoreDBBroker();

        foreach ($days as $day) {
            $error = false;
            $dbBroker->beginTransaction();
            //$now = date('Y-m-d', time());
            //if(strtotime($day) < strtotime($now)){
            //    return;
            //}

            $query1 = "select BZ.BlokZauzeceID
                            from blokzauzece BZ
                            where BZ.Datum = '" . $day . "' AND BZ.RadioStanicaID = " . $this->getRadioStanicaID();

            $row1 = $dbBroker->selectOneRow($query1);
            if (!$row1) {
                $query2 = "select B.BlokID, B.Trajanje
                                from blok B
                                where B.Aktivan = 1";
                $result2 = $dbBroker->selectManyRows($query2);
                $rows = $result2['rows'];
                foreach ($rows as $row) {
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setBlokID($row['BlokID']);
                    $blokZauzece->setDatum($day);
                    $blokZauzece->setZauzetoSekundi(0);
                    $blokZauzece->setPreostaloSekundi($row['Trajanje']);
                    $blokZauzece->setPotvrdjenoSekundi(0);
                    $blokZauzece->setNepotvrdjenoSekundi(0);
                    $blokZauzece->setZauzetaPrva(0);
                    $blokZauzece->setZauzetaDruga(0);
                    $blokZauzece->setRadioStanicaID($this->getRadioStanicaID());
                    $blokZauzece->setDelatnostIDs(',');
                    $blokZauzece->setGlasIDs(',');
                    $result = $dbBroker->insert($blokZauzece);
                    if (!$result) {
                        $error = true;
                    }
                }
            }
            if ($error) {
                $dbBroker->rollback();
            } else {
                $dbBroker->commit();
            }
        }
        $dbBroker->close();
    }



    public function vratiIzabraneBlokoveZaPrikaz($izabraniBlokovi) {
        $izabraniBlokoviZaPrikaz = array();
        foreach ($izabraniBlokovi as $key => $value) {
            $izabraniBlokoviZaPrikaz[$key] = array();
            foreach ($value as $blok => $cena) {
                $niz = explode('/', $blok);
                $blokID = $niz[0];
                $izabraniBlokoviZaPrikaz[$key][] = $blokID;
            }
        }

        return $izabraniBlokoviZaPrikaz;
    }

    public function izabraniPodaciZaPrikaz($tempSpot) {
        $dbBroker = new CoreDBBroker();
        $izabraniBlokovi = $tempSpot['izabraniBlokovi'];
        //$izabraniBlokovi["2015-05-21"]["21/3"]=cena



        //$tempSpot['izabraniBlokoviZaPrikaz'][2015-05-21]=array(block_ids)


        $data = array();
        $izabraniBl = '';
        foreach ($tempSpot['izabraniBlokoviZaPrikaz'] as $key => $value) {

            $izabraniBl = implode(',', $value);

            $query = "SELECT BlokID, 
                            concat_ws(' ', '2012-01-01', VremeStart) as VremeStart,
                            concat_ws(' ', '2012-01-01', VremeEnd) as VremeEnd,
                            Trajanje 
            FROM blok WHERE BlokID IN ($izabraniBl)";
            if ($izabraniBl <> '') {
                $rows = $dbBroker->selectManyRows($query);
                foreach ($rows['rows'] as $row) {



                    //$blokoviPozicije = array($row['BlokID'] . '/1', $row['BlokID'] . '/2', $row['BlokID'] . '/3', $row['BlokID'] . '/4', $row['BlokID'] . '/5', $row['BlokID'] . '/6', $row['BlokID'] . '/7', $row['BlokID'] . '/8', $row['BlokID'] . '/9', $row['BlokID'] . '/10');
                    $blokoviPozicije=array();
                    foreach ($this->positions as $p) {
                        $blokoviPozicije[] = $row['BlokID'] . '/'.$p;
                    }



                    foreach ($blokoviPozicije as $blPoz) {
                        if (in_array($blPoz, array_keys($izabraniBlokovi[$key]))) {


                            $arr15=explode("/",$blPoz);

                            //$pozicija = substr($blPoz, -1, 1);
                            $pozicija =$arr15[1];
                            break;
                        }
                    }

                    $data[$key][] = array(
                        'BlokID' => $row['BlokID'],
                        'VremeStart' => $row['VremeStart'],
                        'VremeEnd' => $row['VremeEnd'],
                        'Trajanje' => $row['Trajanje'],
                        'Flag' => false,
                        'CommercialBlockOrderID' => $pozicija
                    );
                }
            }
        }



/*
        var_dump($tempSpot['zauzetiBlokoviZaPrikaz']);
        exit;*/


        foreach ($tempSpot['zauzetiBlokoviZaPrikaz'] as $key => $value) {//key=datum, $value=array(block_ids)

            $zauzetiBl = '';



            //$zauzetiBl = implode(',', $value);


            foreach ($value as $blockId => $razlog) {

                //$value2=array('blockId' => $block, 'razlog' => 'glas ili delatnost !!!');

                $zauzetiBl.=",".$blockId;

            }
            $zauzetiBl=substr($zauzetiBl,1);

            //var_dump($zauzetiBl);


            $query = "SELECT BlokID, 
                            concat_ws(' ', '2012-01-01', VremeStart) as VremeStart,
                            concat_ws(' ', '2012-01-01', VremeEnd) as VremeEnd,
                            Trajanje 
            FROM blok WHERE BlokID IN ($zauzetiBl)";
            if ($zauzetiBl <> '') {
                $rows = $dbBroker->selectManyRows($query);
                foreach ($rows['rows'] as $row) {
                    $data[$key][] = array(
                        'BlokID' => $row['BlokID'],
                        'Title' => $tempSpot['spotName']." - " .$value[$row['BlokID']],
                        'VremeStart' => $row['VremeStart'],
                        'VremeEnd' => $row['VremeEnd'],
                        'Trajanje' => $row['Trajanje'],
                        'Flag' => true,
                        'CommercialBlockOrderID' => ''
                    );
                }
            }
        }



        return $data;
    }

    public function GetResponse_old() {
        $dbBroker = new CoreDBBroker();
        $response = new stdClass();
        $response->success = true;
        $response->data->capmaignePrice = $this->campaignePrice;
        $response->data->campaigneID = '';
        //$response = '{success:true, data:{"capmaignePrice" :' . $this->campaignePrice . ', ';
        //$schedulerDates = '"schedulerDates" : [';
        //$schedulerCommercial = '"schedulerCommercial" : [';
        $i = 1;
        $j = 1;//redni broj (id) uvek ide za jedan za svaki ubaceni blok prazan ili ne
        $k = 1;//Brojac za spotove

        $konacno = array();
        $schedulerDates = array();
        $schedulerDatesArray = array();
        foreach ($this->tempSpotArray as $tempSpot) {
            $data = $this->izabraniPodaciZaPrikaz($tempSpot);
            $sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];


            foreach ($sortiraniDani as $key => $value) {
                foreach ($value as $dan => $blokovi) {
                    if (!in_array($dan, $schedulerDatesArray)) {
                        $schedulerDatesArray[$i] = $dan;
                        $row1['Id'] = $i;
                        $row1['Name'] = $dan;
                        $schedulerDates[] = $row1;
                        $i++;
                    }
                }
            }
        }




        $schedulerCommercial = array();







        foreach ($this->tempSpotArray as $tempSpot) {


            $data = $this->izabraniPodaciZaPrikaz($tempSpot);








/*
            var_dump($data);
            exit;
*/

            //$sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];
            // dodat ovaj foreach, treba da se sredi


            foreach ($tempSpot['days'] as $dan) {

                //ovo je jedan red u dijagramu

                //if (isset($data[$dan])) {

                    $l = 0;//broj izabranih blokova za odredjeni datum i spot

                    //Promenljiva koja čuva sve blokove koji imaju neku informaciju
                    $blockSelcted = array();
                    foreach ($data[$dan] as $row) {

                        if ($row['Flag']) {
                            $row2['Id'] = $j;
                            $row2['BlokId'] = $row['BlokID'];
                            $row2['DatumBloka'] = $dan;
                            $row2['Title'] = 'Blok zauzet drugim reklamama';
                            $row2['StartDate'] = $row['VremeStart'];
                            $row2['EndDate'] = $row['VremeEnd'];
                            $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                            $row2['Duration'] = $row['Trajanje'];
                            $row2['OtherClient'] = $row['Flag'];
                            $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                            $row2['Color'] = $k;
                            $row2['SpotName'] = $tempSpot['spotName'];
                            $row2['SpotID'] = $tempSpot['spotID'];
                            array_push($blockSelcted, $row['BlokID']);
                        } else {
                            $row2['Id'] = $j;
                            $row2['BlokId'] = $row['BlokID'];
                            $row2['DatumBloka'] = $dan;
                            $row2['Title'] = $this->naziv;
                            $row2['StartDate'] = $row['VremeStart'];
                            $row2['EndDate'] = $row['VremeEnd'];
                            $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                            $row2['Duration'] = $row['Trajanje'];
                            $row2['OtherClient'] = $row['Flag'];
                            $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                            $row2['Color'] = $k;
                            $row2['SpotName'] = $tempSpot['spotName'];
                            $row2['SpotID'] = $tempSpot['spotID'];
                            array_push($blockSelcted, $row['BlokID']);
                        }

                        //MOramo da odstranimo visestruke nedostupne blokove zbog vise spotova
                        if ($row2['OtherClient']) {//zauzet blok
                            $blockExist = false;
                            /*
                            foreach ($schedulerCommercial as $value) {
                                if ( ($value['BlokId'] == $row2['BlokId'])    ) {//&& ($value['DatumBloka'] == $row2['DatumBloka'])
                                    $blockExist = true;//$blockExist || true;
                                }
                            }*/
                            if (!$blockExist) {
                                $schedulerCommercial[] = $row2;
                                $j++;
                            }
                        } else {
                            $schedulerCommercial[] = $row2;
                            $j++;
                            $l++;
                        }






                    }

                    //Ovde treba da dodamo i za svaki beli blok da s epošalju osnovne informaciej kako bi mogli da radimo dodavnej na njemu
                    //Popunićemo sve one blokIDeve koji s ene pojavljuju 
                    $query1 = "SELECT * from blok";
                    $result1 = $dbBroker->selectManyRows($query1);
                    foreach ($result1['rows'] as $row) {
                        if (!in_array($row['BlokID'], $blockSelcted)) {
                            $row3['Id'] = $j;
                            $row3['ResourceId'] = array_search($dan, $schedulerDatesArray);
                            $row3['OtherClient'] = false;
                            $row3['BlokId'] = $row['BlokID'];
                            $row3['DatumBloka'] = $dan;
                            $row3['Color'] = 0;
                            $row3['StartDate'] = '2012-01-01 ' . $row['VremeStart'];
                            $row3['EndDate'] = '2012-01-01 ' . $row['VremeEnd'];
                            $schedulerCommercial[] = $row3;
                            $j++;
                        }
                    }

                    foreach ($schedulerDates as $key => $value) {
                        if ($value['Name'] == $dan) {
                            $schedulerDates[$key]['Frequency'] = $l;
                        }
                    }
                //}
            }//petlja kroz dane za jedan spot


            $k++;
        }//petlja kroz spotove


        $response->data->schedulerDates = $schedulerDates;
        $response->data->schedulerCommercial = $schedulerCommercial;

        //return $response;

        return json_encode($response);
    }







    public function GetResponse() {
        $dbBroker = new CoreDBBroker();
        $response = new stdClass();
        $response->success = true;


        $response->data->sablonId = 0;
        if(isset($this->sablonId)){//sablon kampanja
            $response->data->sablonId = $this->sablonId;
        }

        $response->data->popust = $this->popust;

        $response->data->spotBroj = count($this->spotArray);


        $response->data->capmaignePrice = $this->campaignePrice;
        $response->data->campaigneID = '';


        //$response = '{success:true, data:{"capmaignePrice" :' . $this->campaignePrice . ', ';
        //$schedulerDates = '"schedulerDates" : [';
        //$schedulerCommercial = '"schedulerCommercial" : [';
        $i = 1;
        $j = 1;//redni broj (id) uvek ide za jedan za svaki ubaceni blok prazan ili ne
        $k = 1;//Brojac za spotove



        $konacno = array();
        $schedulerDates = array();
        $schedulerDatesArray = array();


/*
        foreach ($this->tempSpotArray as $tempSpot) {
            $data = $this->izabraniPodaciZaPrikaz($tempSpot);
            $sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];


            foreach ($sortiraniDani as $key => $value) {
                foreach ($value as $dan => $blokovi) {
                    if (!in_array($dan, $schedulerDatesArray)) {
                        $schedulerDatesArray[$i] = $dan;
                        $row1['Id'] = $i;
                        $row1['Name'] = $dan;

                        $schedulerDates[] = $row1;

                        $i++;
                    }
                }
            }
        }*/


        foreach ($this->allDays as $key => $dan) {
            $schedulerDatesArray[$i] = $dan;
            $row1['Id'] = $i;
            $row1['Name'] = $dan;
            $schedulerDates[] = $row1;
            $i++;
        }




        $schedulerCommercial = array();

        foreach ($schedulerDates as $key => $value) {
            $dan=$value['Name'];
            $blockSelcted[$dan]=array();
        }




        foreach ($this->tempSpotArray as $tempSpot) {


/*
            var_dump($tempSpot['izabraniBlokoviZaPrikaz']);
*/

            $data = $this->izabraniPodaciZaPrikaz($tempSpot);





            foreach ($tempSpot['days'] as $dan) {

                //ovo je jedan red u dijagramu

                $l = 0;//broj izabranih blokova za odredjeni datum i spot

                foreach ($data[$dan] as $row) {




                    if ($row['Flag']) {
                        $row2['Id'] = $j;
                        $row2['BlokId'] = $row['BlokID'];
                        $row2['DatumBloka'] = $dan;
                        $row2['Title'] =  $row['Title'];//'Blok zauzet drugim reklamama';
                        $row2['StartDate'] = $row['VremeStart'];
                        $row2['EndDate'] = $row['VremeEnd'];
                        $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                        $row2['Duration'] = $row['Trajanje'];
                        $row2['OtherClient'] = $row['Flag'];
                        $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                        $row2['Color'] = $k;
                        $row2['SpotName'] = $tempSpot['spotName'];
                        $row2['SpotID'] = $tempSpot['spotID'];


                        $row2['RadioStanicaID'] = $this->radioStanicaId;

                        array_push($blockSelcted[$dan], $row['BlokID']);
                    } else {
                        $row2['Id'] = $j;
                        $row2['BlokId'] = $row['BlokID'];
                        $row2['DatumBloka'] = $dan;
                        $row2['Title'] = $this->naziv;
                        $row2['StartDate'] = $row['VremeStart'];
                        $row2['EndDate'] = $row['VremeEnd'];
                        $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                        $row2['Duration'] = $row['Trajanje'];
                        $row2['OtherClient'] = $row['Flag'];
                        $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                        $row2['Color'] = $k;
                        $row2['SpotName'] = $tempSpot['spotName'];
                        $row2['SpotID'] = $tempSpot['spotID'];

                        $row2['RadioStanicaID'] = $this->radioStanicaId;

                        array_push($blockSelcted[$dan], $row['BlokID']);
                    }

                    //MOramo da odstranimo visestruke nedostupne blokove zbog vise spotova
                    if ($row2['OtherClient']) {//zauzet blok (crveni)
                        $blockExist = false;

                        foreach ($schedulerCommercial as $key => $value) {
                            if ( ($value['BlokId'] == $row2['BlokId'])  &&  ($value['DatumBloka'] == $row2['DatumBloka'])  ) {
                                $blockExist = true;
                                break;
                            }
                        }

                        if (!$blockExist) {
                            $schedulerCommercial[] = $row2;
                            $j++;
                        }else{
                            if($value['OtherClient']){//zauzet takodje vec u dijagramu (crveni)
                                $schedulerCommercial[$key]['Title'].="<br>".$row2['Title'];
                                $schedulerCommercial[$key]['SpotName'].="<br>".$row2['SpotName'];
                            }
                            //exit;
                        }
                    } else {

                        //provera u slucaju da je poziciju vec zauzeo zauzet blok (crveni) od prethodnog spota (onda ga pregazim)
                        $blockExist = false;

                        foreach ($schedulerCommercial as $key => $value) {
                            if ( ($value['BlokId'] == $row2['BlokId'])  &&  ($value['DatumBloka'] == $row2['DatumBloka'])  ) {
                                $blockExist = true;
                                $schedulerCommercial[$key]=$row2;
                                $l++;
                                break;
                            }
                        }

                        if (!$blockExist) {
                            $schedulerCommercial[] = $row2;
                            $j++;
                            $l++;
                        }
                    }

                }



                foreach ($schedulerDates as $key => $value) {
                    if ($value['Name'] == $dan) {
                        $schedulerDates[$key]['Frequency'] .= $l." - ".$tempSpot['spotName']."<br/>";
                    }
                }
                //}
            }//petlja kroz dane za jedan spot






            $k++;
        }//petlja kroz spotove
        /*var_dump($schedulerCommercial);
        exit;*/

        //Ovde treba da dodamo i za svaki beli blok da s epošalju osnovne informaciej kako bi mogli da radimo dodavnej na njemu
        //Popunićemo sve one blokIDeve koji s ene pojavljuju
        $query1 = "SELECT * from blok";
        $result1 = $dbBroker->selectManyRows($query1);

        foreach ($result1['rows'] as $row) {

            foreach ($schedulerDates as $key => $value) {

                $dan=$value['Name'];

                if (!in_array($row['BlokID'], $blockSelcted[$dan])) {
                    $row3['Id'] = $j;
                    $row3['ResourceId'] = array_search($dan, $schedulerDatesArray);
                    $row3['OtherClient'] = false;
                    $row3['BlokId'] = $row['BlokID'];
                    $row3['DatumBloka'] = $dan;
                    $row3['Color'] = 0;
                    $row3['StartDate'] = '2012-01-01 ' . $row['VremeStart'];
                    $row3['EndDate'] = '2012-01-01 ' . $row['VremeEnd'];

                    $row3['RadioStanicaID'] = $this->radioStanicaId;

                    $schedulerCommercial[] = $row3;
                    $j++;
                }
            }

        }

        $response->data->schedulerDates = $schedulerDates;
        $response->data->schedulerCommercial = $schedulerCommercial;

        //return $response;

        return json_encode($response);
    }







    public function GetResponse_dani_prva_petlja() {
        $dbBroker = new CoreDBBroker();
        $response = new stdClass();
        $response->success = true;
        $response->data->capmaignePrice = $this->campaignePrice;
        $response->data->campaigneID = '';
        //$response = '{success:true, data:{"capmaignePrice" :' . $this->campaignePrice . ', ';
        //$schedulerDates = '"schedulerDates" : [';
        //$schedulerCommercial = '"schedulerCommercial" : [';
        $i = 1;
        $j = 1;//redni broj (id) uvek ide za jedan za svaki ubaceni blok prazan ili ne
        $k = 1;//Brojac za spotove

        $konacno = array();
        $schedulerDates = array();
        $schedulerDatesArray = array();
        foreach ($this->tempSpotArray as $tempSpot) {
            $data = $this->izabraniPodaciZaPrikaz($tempSpot);
            $sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];


            foreach ($sortiraniDani as $key => $value) {
                foreach ($value as $dan => $blokovi) {
                    if (!in_array($dan, $schedulerDatesArray)) {
                        $schedulerDatesArray[$i] = $dan;
                        $row1['Id'] = $i;
                        $row1['Name'] = $dan;
                        $schedulerDates[] = $row1;
                        $i++;
                    }
                }
            }
        }




        $schedulerCommercial = array();





        foreach ($schedulerDates as $key => $value) {
            $dan = $value['Name'];


            $blockSelcted = array();


            $l = array();//broj izabranih blokova za odredjeni datum i spot

            $k=1;
            foreach ($this->tempSpotArray as $tempSpot) {


                $l[$k]=0;



                $data = $this->izabraniPodaciZaPrikaz($tempSpot);


                /*
                            var_dump($data);
                            exit;
                */

                //$sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];
                // dodat ovaj foreach, treba da se sredi


                foreach ($tempSpot['days'] as $dan2) {

                    if($dan==$dan2) {
                        //ovo je jedan red u dijagramu

                        //if (isset($data[$dan])) {

                        //$l = 0;//broj izabranih blokova za odredjeni datum i spot

                        //Promenljiva koja čuva sve blokove koji imaju neku informaciju
                        //$blockSelcted = array();
                        foreach ($data[$dan] as $row) {

                            if ($row['Flag']) {

                                $row2['Id'] = $j;
                                $row2['BlokId'] = $row['BlokID'];
                                $row2['DatumBloka'] = $dan;
                                $row2['Title'] = 'Blok zauzet drugim reklamama';
                                $row2['StartDate'] = $row['VremeStart'];
                                $row2['EndDate'] = $row['VremeEnd'];
                                $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                                $row2['Duration'] = $row['Trajanje'];
                                $row2['OtherClient'] = $row['Flag'];
                                $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                                $row2['Color'] = $k;
                                $row2['SpotName'] = $tempSpot['spotName'];
                                $row2['SpotID'] = $tempSpot['spotID'];

                                array_push($blockSelcted, $row['BlokID']);

                            } else {
                                $row2['Id'] = $j;
                                $row2['BlokId'] = $row['BlokID'];
                                $row2['DatumBloka'] = $dan;
                                $row2['Title'] = $this->naziv;
                                $row2['StartDate'] = $row['VremeStart'];
                                $row2['EndDate'] = $row['VremeEnd'];
                                $row2['ResourceId'] = array_search($dan, $schedulerDatesArray);
                                $row2['Duration'] = $row['Trajanje'];
                                $row2['OtherClient'] = $row['Flag'];
                                $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                                $row2['Color'] = $k;
                                $row2['SpotName'] = $tempSpot['spotName'];
                                $row2['SpotID'] = $tempSpot['spotID'];
                                array_push($blockSelcted, $row['BlokID']);
                            }

                            //MOramo da odstranimo visestruke nedostupne blokove zbog vise spotova
                            if ($row2['OtherClient']) {//zauzet blok
                                $blockExist = false;

                                foreach ($schedulerCommercial as $value) {
                                    if (($value['BlokId'] == $row2['BlokId']) && ($value['DatumBloka'] == $row2['DatumBloka'])) {
                                        $blockExist = true;
                                    }
                                }

                                if (!$blockExist) {
                                    $schedulerCommercial[] = $row2;
                                    $j++;
                                }
                            } else {
                                $schedulerCommercial[] = $row2;
                                $j++;
                                $l[$k]++;
                            }


                        }



                    }//if dan=dan
                    //}
                }//petlja kroz dane za jedan spot


                $k++;
            }//petlja kroz spotove






            foreach ($schedulerDates as $key => $value) {
                if ($value['Name'] == $dan) {
                    $schedulerDates[$key]['Frequency']="";
                    foreach ($l as $key_l => $value_l) {
                        $schedulerDates[$key]['Frequency'] .= $key_l;
                    }
                }
            }





            //Ovde treba da dodamo i za svaki beli blok da s epošalju osnovne informaciej kako bi mogli da radimo dodavnej na njemu
            //Popunićemo sve one blokIDeve koji s ene pojavljuju

            $query1 = "SELECT * from blok";
            $result1 = $dbBroker->selectManyRows($query1);
            foreach ($result1['rows'] as $row) {
                if (!in_array($row['BlokID'], $blockSelcted)) {
                    $row3['Id'] = $j;
                    $row3['ResourceId'] = array_search($dan, $schedulerDatesArray);
                    $row3['OtherClient'] = false;
                    $row3['BlokId'] = $row['BlokID'];
                    $row3['DatumBloka'] = $dan;
                    $row3['Color'] = 0;
                    $row3['StartDate'] = '2012-01-01 ' . $row['VremeStart'];
                    $row3['EndDate'] = '2012-01-01 ' . $row['VremeEnd'];
                    $schedulerCommercial[] = $row3;
                    $j++;
                }
            }



        }//kroz datume

        /*
        var_dump($schedulerCommercial);
        exit;*/











        $response->data->schedulerDates = $schedulerDates;
        $response->data->schedulerCommercial = $schedulerCommercial;

        //return $response;

        return json_encode($response);
    }
















    public function GetResponse_dupla() {
        $dbBroker = new CoreDBBroker();
        $response = new stdClass();
        $response->success = true;
        $response->data->capmaignePrice = $this->campaignePrice;
        $response->data->campaigneID = '';
        //$response = '{success:true, data:{"capmaignePrice" :' . $this->campaignePrice . ', ';
        //$schedulerDates = '"schedulerDates" : [';
        //$schedulerCommercial = '"schedulerCommercial" : [';
        $i = 1;//dani resource id
        $j = 1;//redni broj (id) uvek ide za jedan za svaki ubaceni blok prazan ili ne
        $k = 1;//Brojac za spotove

        $konacno = array();
        $schedulerDates = array();
        $schedulerDatesArray = array();
        foreach ($this->tempSpotArray as $tempSpot) {
            $data = $this->izabraniPodaciZaPrikaz($tempSpot);
            $sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];


            foreach ($sortiraniDani as $key => $value) {
                foreach ($value as $dan => $blokovi) {
                    if (!in_array($dan, $schedulerDatesArray)) {

                        $schedulerDatesArray[$i] = $dan;
                        $row1['Id'] = $i;
                        $row1['Name'] = $dan;
                        $schedulerDates[] = $row1;

                        $i++;

                    }
                }
            }
        }



        $schedulerDates2=array();

        foreach ($schedulerDates as $row) {
            $s=1;
            $row2=$row;
            foreach ($this->tempSpotArray as $tempSpot) {

                $id_pom=$row['Id']."/".$s;
                $row2['Id']=$id_pom;
                $schedulerDates2[]=$row2;

                $s++;
            }
        }

        $schedulerDates=$schedulerDates2;

/*
        var_dump($schedulerDates);
        exit;
*/

        $schedulerCommercial = array();







        foreach ($this->tempSpotArray as $tempSpot) {


            $data = $this->izabraniPodaciZaPrikaz($tempSpot);




            /*
                        var_dump($data);
                        exit;
            */

            //$sortiraniDani = $tempSpot['sortiraniDostupniZeljeniDani'];
            // dodat ovaj foreach, treba da se sredi


            foreach ($tempSpot['days'] as $dan) {

                //ovo je jedan red u dijagramu

                //if (isset($data[$dan])) {

                $l = 0;//broj izabranih blokova za odredjeni datum i spot

                //Promenljiva koja čuva sve blokove koji imaju neku informaciju
                $blockSelcted = array();
                foreach ($data[$dan] as $row) {

                    if ($row['Flag']) {
                        $row2['Id'] = $j;
                        $row2['BlokId'] = $row['BlokID'];
                        $row2['DatumBloka'] = $dan;
                        $row2['Title'] = 'Blok zauzet drugim reklamama';
                        $row2['StartDate'] = $row['VremeStart'];
                        $row2['EndDate'] = $row['VremeEnd'];
                        $row2['ResourceId'] = array_search($dan, $schedulerDatesArray)."/".$k;
                        $row2['Duration'] = $row['Trajanje'];
                        $row2['OtherClient'] = $row['Flag'];
                        $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                        $row2['Color'] = $k;
                        $row2['SpotName'] = $tempSpot['spotName'];
                        $row2['SpotID'] = $tempSpot['spotID'];
                        array_push($blockSelcted, $row['BlokID']);
                    } else {
                        $row2['Id'] = $j;
                        $row2['BlokId'] = $row['BlokID'];
                        $row2['DatumBloka'] = $dan;
                        $row2['Title'] = $this->naziv;
                        $row2['StartDate'] = $row['VremeStart'];
                        $row2['EndDate'] = $row['VremeEnd'];
                        $row2['ResourceId'] = array_search($dan, $schedulerDatesArray)."/".$k;
                        $row2['Duration'] = $row['Trajanje'];
                        $row2['OtherClient'] = $row['Flag'];
                        $row2['CommercialBlockOrderID'] = $row['CommercialBlockOrderID'];
                        $row2['Color'] = $k;
                        $row2['SpotName'] = $tempSpot['spotName'];
                        $row2['SpotID'] = $tempSpot['spotID'];
                        array_push($blockSelcted, $row['BlokID']);
                    }

                    //MOramo da odstranimo visestruke nedostupne blokove zbog vise spotova
                    if ($row2['OtherClient']) {//zauzet blok
                        $blockExist = false;
                        /*
                        foreach ($schedulerCommercial as $value) {
                            if ( ($value['BlokId'] == $row2['BlokId'])    ) {//&& ($value['DatumBloka'] == $row2['DatumBloka'])
                                $blockExist = true;//$blockExist || true;
                            }
                        }*/
                        if (!$blockExist) {
                            $schedulerCommercial[] = $row2;
                            $j++;
                        }
                    } else {
                        $schedulerCommercial[] = $row2;
                        $j++;
                        $l++;
                    }






                }

                //Ovde treba da dodamo i za svaki beli blok da s epošalju osnovne informaciej kako bi mogli da radimo dodavnej na njemu
                //Popunićemo sve one blokIDeve koji s ene pojavljuju
                $query1 = "SELECT * from blok";
                $result1 = $dbBroker->selectManyRows($query1);
                foreach ($result1['rows'] as $row) {
                    if (!in_array($row['BlokID'], $blockSelcted)) {
                        $row3['Id'] = $j;
                        $row3['ResourceId'] = array_search($dan, $schedulerDatesArray)."/".$k;
                        $row3['OtherClient'] = false;
                        $row3['BlokId'] = $row['BlokID'];
                        $row3['DatumBloka'] = $dan;
                        $row3['Color'] = 0;
                        $row3['StartDate'] = '2012-01-01 ' . $row['VremeStart'];
                        $row3['EndDate'] = '2012-01-01 ' . $row['VremeEnd'];
                        $schedulerCommercial[] = $row3;
                        $j++;
                    }
                }

                foreach ($schedulerDates as $key => $value) {





                    if (   ($value['Name'] == $dan) && (strpos($value['Id'],"/".$k))    ) {
                        $schedulerDates[$key]['Frequency'] = $l;
                        $schedulerDates[$key]['Spot'] = $tempSpot['spotName'];
                    }
                }
                //}
            }//petlja kroz dane za jedan spot


            $k++;
        }//petlja kroz spotove


        $response->data->schedulerDates = $schedulerDates;
        $response->data->schedulerCommercial = $schedulerCommercial;

        //return $response;

        return json_encode($response);
    }


public function GetResponse_new()
{
    $data = '{"success":true,"data":{"capmaignePrice":343.2,"campaigneID":"","schedulerDates":[{"Id":1,"Name":"2015-05-28","Frequency":6},{"Id":2,"Name":"2015-05-21","Frequency":8}],"schedulerCommercial":[

    {
    "Id":87,
    "ResourceId":1,
    "OtherClient":false,
    "BlokId":"1",
    "DatumBloka":"2015-05-21",
    "Color":2,
    "StartDate":"2012-01-01 06:15:00",
    "EndDate":"2012-01-01 06:17:30"
    },


    {"Id":2,"BlokId":"5","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 07:15:00","EndDate":"2012-01-01 07:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":3,"BlokId":"9","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 08:15:00","EndDate":"2012-01-01 08:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},



    {"Id":4,
    "BlokId":"17",
    "DatumBloka":"2015-05-21",
    "Title":"naziv kamp",
    "StartDate":"2012-01-01 10:15:00",
    "EndDate":"2012-01-01 10:17:30",
    "ResourceId":1,
    "Duration":"150",
    "OtherClient":false,
    "CommercialBlockOrderID":"1",
    "Color":1,"SpotName":"spottt1","SpotID":2075
    },

    {"Id":5,"BlokId":"25","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 12:15:00","EndDate":"2012-01-01 12:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},



    {"Id":6,"BlokId":"37","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 15:15:00","EndDate":"2012-01-01 15:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":7,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":8,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":9,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":10,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":11,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":12,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":13,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":14,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":15,"ResourceId":1,"OtherClient":false,"BlokId":"3","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:45:00","EndDate":"2012-01-01 06:47:30"},{"Id":16,"ResourceId":1,"OtherClient":false,"BlokId":"7","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:45:00","EndDate":"2012-01-01 07:47:30"},{"Id":17,"ResourceId":1,"OtherClient":false,"BlokId":"10","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:30:00","EndDate":"2012-01-01 08:30:30"},{"Id":18,"ResourceId":1,"OtherClient":false,"BlokId":"11","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:45:00","EndDate":"2012-01-01 08:47:30"},{"Id":19,"ResourceId":1,"OtherClient":false,"BlokId":"12","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:59:30","EndDate":"2012-01-01 09:00:00"},{"Id":20,"ResourceId":1,"OtherClient":false,"BlokId":"13","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30"},{"Id":21,"ResourceId":1,"OtherClient":false,"BlokId":"14","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:30:00","EndDate":"2012-01-01 09:30:30"},{"Id":22,"ResourceId":1,"OtherClient":false,"BlokId":"15","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:45:00","EndDate":"2012-01-01 09:47:30"},{"Id":23,"ResourceId":1,"OtherClient":false,"BlokId":"16","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:59:30","EndDate":"2012-01-01 10:00:00"},{"Id":24,"ResourceId":1,"OtherClient":false,"BlokId":"18","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:30:00","EndDate":"2012-01-01 10:30:30"},{"Id":25,"ResourceId":1,"OtherClient":false,"BlokId":"19","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:45:00","EndDate":"2012-01-01 10:47:30"},{"Id":26,"ResourceId":1,"OtherClient":false,"BlokId":"20","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:59:30","EndDate":"2012-01-01 11:00:00"},{"Id":27,"ResourceId":1,"OtherClient":false,"BlokId":"21","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30"},{"Id":28,"ResourceId":1,"OtherClient":false,"BlokId":"22","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:30:00","EndDate":"2012-01-01 11:30:30"},{"Id":29,"ResourceId":1,"OtherClient":false,"BlokId":"23","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:45:00","EndDate":"2012-01-01 11:47:30"},{"Id":30,"ResourceId":1,"OtherClient":false,"BlokId":"26","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:30:00","EndDate":"2012-01-01 12:30:30"},{"Id":31,"ResourceId":1,"OtherClient":false,"BlokId":"27","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30"},{"Id":32,"ResourceId":1,"OtherClient":false,"BlokId":"28","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:59:30","EndDate":"2012-01-01 13:00:00"},{"Id":33,"ResourceId":1,"OtherClient":false,"BlokId":"29","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:15:00","EndDate":"2012-01-01 13:17:30"},{"Id":34,"ResourceId":1,"OtherClient":false,"BlokId":"30","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:30:00","EndDate":"2012-01-01 13:30:30"},{"Id":35,"ResourceId":1,"OtherClient":false,"BlokId":"31","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30"},{"Id":36,"ResourceId":1,"OtherClient":false,"BlokId":"32","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:59:30","EndDate":"2012-01-01 14:00:00"},{"Id":37,"ResourceId":1,"OtherClient":false,"BlokId":"33","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30"},{"Id":38,"ResourceId":1,"OtherClient":false,"BlokId":"34","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:30:00","EndDate":"2012-01-01 14:30:30"},{"Id":39,"ResourceId":1,"OtherClient":false,"BlokId":"35","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:45:00","EndDate":"2012-01-01 14:47:30"},{"Id":40,"ResourceId":1,"OtherClient":false,"BlokId":"36","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:59:30","EndDate":"2012-01-01 15:00:00"},{"Id":41,"ResourceId":1,"OtherClient":false,"BlokId":"38","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:30:00","EndDate":"2012-01-01 15:30:30"},{"Id":42,"ResourceId":1,"OtherClient":false,"BlokId":"39","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:45:00","EndDate":"2012-01-01 15:47:30"},{"Id":43,"ResourceId":1,"OtherClient":false,"BlokId":"40","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:59:30","EndDate":"2012-01-01 16:00:00"},{"Id":44,"ResourceId":1,"OtherClient":false,"BlokId":"41","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:15:00","EndDate":"2012-01-01 16:17:30"},{"Id":45,"ResourceId":1,"OtherClient":false,"BlokId":"42","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:30:00","EndDate":"2012-01-01 16:30:30"},{"Id":46,"ResourceId":1,"OtherClient":false,"BlokId":"43","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:45:00","EndDate":"2012-01-01 16:47:30"},{"Id":47,"ResourceId":1,"OtherClient":false,"BlokId":"44","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:59:30","EndDate":"2012-01-01 17:00:00"},{"Id":48,"ResourceId":1,"OtherClient":false,"BlokId":"45","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30"},{"Id":49,"ResourceId":1,"OtherClient":false,"BlokId":"46","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:30:00","EndDate":"2012-01-01 17:30:30"},{"Id":50,"ResourceId":1,"OtherClient":false,"BlokId":"47","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:45:00","EndDate":"2012-01-01 17:47:30"},{"Id":51,"ResourceId":1,"OtherClient":false,"BlokId":"48","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:59:30","EndDate":"2012-01-01 18:00:00"},{"Id":52,"ResourceId":1,"OtherClient":false,"BlokId":"49","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30"},{"Id":53,"ResourceId":1,"OtherClient":false,"BlokId":"51","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:45:00","EndDate":"2012-01-01 18:47:30"},{"Id":54,"ResourceId":1,"OtherClient":false,"BlokId":"52","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:59:30","EndDate":"2012-01-01 19:00:00"},{"Id":55,"ResourceId":1,"OtherClient":false,"BlokId":"53","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:15:00","EndDate":"2012-01-01 19:17:30"},{"Id":56,"ResourceId":1,"OtherClient":false,"BlokId":"54","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:30:00","EndDate":"2012-01-01 19:30:30"},{"Id":57,"ResourceId":1,"OtherClient":false,"BlokId":"55","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:45:00","EndDate":"2012-01-01 19:47:30"},{"Id":58,"ResourceId":1,"OtherClient":false,"BlokId":"57","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:15:00","EndDate":"2012-01-01 20:17:30"},{"Id":59,"ResourceId":1,"OtherClient":false,"BlokId":"58","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:30:00","EndDate":"2012-01-01 20:30:30"},{"Id":60,"ResourceId":1,"OtherClient":false,"BlokId":"59","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:45:00","EndDate":"2012-01-01 20:47:30"},{"Id":61,"ResourceId":1,"OtherClient":false,"BlokId":"60","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:59:30","EndDate":"2012-01-01 21:00:00"},{"Id":62,"ResourceId":1,"OtherClient":false,"BlokId":"61","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:15:00","EndDate":"2012-01-01 21:17:30"},{"Id":63,"ResourceId":1,"OtherClient":false,"BlokId":"62","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:30:00","EndDate":"2012-01-01 21:30:30"},{"Id":64,"ResourceId":1,"OtherClient":false,"BlokId":"63","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:45:00","EndDate":"2012-01-01 21:47:30"},{"Id":65,"ResourceId":1,"OtherClient":false,"BlokId":"64","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:59:30","EndDate":"2012-01-01 22:00:00"},{"Id":66,"ResourceId":1,"OtherClient":false,"BlokId":"65","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:15:00","EndDate":"2012-01-01 22:17:30"},{"Id":67,"ResourceId":1,"OtherClient":false,"BlokId":"66","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:30:00","EndDate":"2012-01-01 22:30:30"},{"Id":68,"ResourceId":1,"OtherClient":false,"BlokId":"67","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:45:00","EndDate":"2012-01-01 22:47:30"},{"Id":69,"ResourceId":1,"OtherClient":false,"BlokId":"68","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:59:30","EndDate":"2012-01-01 23:00:00"},{"Id":70,"ResourceId":1,"OtherClient":false,"BlokId":"69","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:15:00","EndDate":"2012-01-01 23:17:30"},{"Id":71,"ResourceId":1,"OtherClient":false,"BlokId":"70","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:30:00","EndDate":"2012-01-01 23:30:30"},{"Id":72,"ResourceId":1,"OtherClient":false,"BlokId":"71","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:45:00","EndDate":"2012-01-01 23:47:30"},{"Id":73,"BlokId":"13","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":74,"BlokId":"21","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":75,"BlokId":"31","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":76,"BlokId":"33","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":77,"BlokId":"45","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":78,"BlokId":"49","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":79,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":80,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":81,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":82,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":83,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":84,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":85,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":86,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},





    {
    "Id":87,
    "ResourceId":1,
    "OtherClient":false,
    "BlokId":"1",
    "DatumBloka":"2015-05-21",
    "Color":1,
    "StartDate":"2012-01-01 06:15:00",
    "EndDate":"2012-01-01 06:17:30"
    },

    {"Id":88,"ResourceId":1,"OtherClient":false,"BlokId":"3","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:45:00","EndDate":"2012-01-01 06:47:30"},{"Id":89,"ResourceId":1,"OtherClient":false,"BlokId":"5","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:15:00","EndDate":"2012-01-01 07:17:30"},{"Id":90,"ResourceId":1,"OtherClient":false,"BlokId":"7","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:45:00","EndDate":"2012-01-01 07:47:30"},{"Id":91,"ResourceId":1,"OtherClient":false,"BlokId":"9","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:15:00","EndDate":"2012-01-01 08:17:30"},{"Id":92,"ResourceId":1,"OtherClient":false,"BlokId":"10","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:30:00","EndDate":"2012-01-01 08:30:30"},{"Id":93,"ResourceId":1,"OtherClient":false,"BlokId":"11","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:45:00","EndDate":"2012-01-01 08:47:30"},{"Id":94,"ResourceId":1,"OtherClient":false,"BlokId":"12","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:59:30","EndDate":"2012-01-01 09:00:00"},{"Id":95,"ResourceId":1,"OtherClient":false,"BlokId":"14","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:30:00","EndDate":"2012-01-01 09:30:30"},{"Id":96,"ResourceId":1,"OtherClient":false,"BlokId":"15","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:45:00","EndDate":"2012-01-01 09:47:30"},{"Id":97,"ResourceId":1,"OtherClient":false,"BlokId":"16","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:59:30","EndDate":"2012-01-01 10:00:00"},



    {"Id":98,"ResourceId":1,"OtherClient":false,"BlokId":"17","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:15:00","EndDate":"2012-01-01 10:17:30"},




    {"Id":99,"ResourceId":1,"OtherClient":false,"BlokId":"18","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:30:00","EndDate":"2012-01-01 10:30:30"},{"Id":100,"ResourceId":1,"OtherClient":false,"BlokId":"19","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:45:00","EndDate":"2012-01-01 10:47:30"},{"Id":101,"ResourceId":1,"OtherClient":false,"BlokId":"20","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:59:30","EndDate":"2012-01-01 11:00:00"},{"Id":102,"ResourceId":1,"OtherClient":false,"BlokId":"22","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:30:00","EndDate":"2012-01-01 11:30:30"},{"Id":103,"ResourceId":1,"OtherClient":false,"BlokId":"23","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:45:00","EndDate":"2012-01-01 11:47:30"},{"Id":104,"ResourceId":1,"OtherClient":false,"BlokId":"25","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:15:00","EndDate":"2012-01-01 12:17:30"},{"Id":105,"ResourceId":1,"OtherClient":false,"BlokId":"26","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:30:00","EndDate":"2012-01-01 12:30:30"},{"Id":106,"ResourceId":1,"OtherClient":false,"BlokId":"27","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30"},{"Id":107,"ResourceId":1,"OtherClient":false,"BlokId":"28","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:59:30","EndDate":"2012-01-01 13:00:00"},{"Id":108,"ResourceId":1,"OtherClient":false,"BlokId":"29","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:15:00","EndDate":"2012-01-01 13:17:30"},{"Id":109,"ResourceId":1,"OtherClient":false,"BlokId":"30","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:30:00","EndDate":"2012-01-01 13:30:30"},{"Id":110,"ResourceId":1,"OtherClient":false,"BlokId":"32","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:59:30","EndDate":"2012-01-01 14:00:00"},{"Id":111,"ResourceId":1,"OtherClient":false,"BlokId":"34","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:30:00","EndDate":"2012-01-01 14:30:30"},{"Id":112,"ResourceId":1,"OtherClient":false,"BlokId":"35","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:45:00","EndDate":"2012-01-01 14:47:30"},{"Id":113,"ResourceId":1,"OtherClient":false,"BlokId":"36","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:59:30","EndDate":"2012-01-01 15:00:00"},{"Id":114,"ResourceId":1,"OtherClient":false,"BlokId":"37","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:15:00","EndDate":"2012-01-01 15:17:30"},{"Id":115,"ResourceId":1,"OtherClient":false,"BlokId":"38","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:30:00","EndDate":"2012-01-01 15:30:30"},{"Id":116,"ResourceId":1,"OtherClient":false,"BlokId":"39","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:45:00","EndDate":"2012-01-01 15:47:30"},{"Id":117,"ResourceId":1,"OtherClient":false,"BlokId":"40","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:59:30","EndDate":"2012-01-01 16:00:00"},{"Id":118,"ResourceId":1,"OtherClient":false,"BlokId":"41","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:15:00","EndDate":"2012-01-01 16:17:30"},{"Id":119,"ResourceId":1,"OtherClient":false,"BlokId":"42","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:30:00","EndDate":"2012-01-01 16:30:30"},{"Id":120,"ResourceId":1,"OtherClient":false,"BlokId":"43","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:45:00","EndDate":"2012-01-01 16:47:30"},{"Id":121,"ResourceId":1,"OtherClient":false,"BlokId":"44","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:59:30","EndDate":"2012-01-01 17:00:00"},{"Id":122,"ResourceId":1,"OtherClient":false,"BlokId":"46","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:30:00","EndDate":"2012-01-01 17:30:30"},{"Id":123,"ResourceId":1,"OtherClient":false,"BlokId":"47","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:45:00","EndDate":"2012-01-01 17:47:30"},{"Id":124,"ResourceId":1,"OtherClient":false,"BlokId":"48","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:59:30","EndDate":"2012-01-01 18:00:00"},{"Id":125,"ResourceId":1,"OtherClient":false,"BlokId":"51","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:45:00","EndDate":"2012-01-01 18:47:30"},{"Id":126,"ResourceId":1,"OtherClient":false,"BlokId":"52","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:59:30","EndDate":"2012-01-01 19:00:00"},{"Id":127,"ResourceId":1,"OtherClient":false,"BlokId":"53","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:15:00","EndDate":"2012-01-01 19:17:30"},{"Id":128,"ResourceId":1,"OtherClient":false,"BlokId":"54","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:30:00","EndDate":"2012-01-01 19:30:30"},{"Id":129,"ResourceId":1,"OtherClient":false,"BlokId":"55","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:45:00","EndDate":"2012-01-01 19:47:30"},{"Id":130,"ResourceId":1,"OtherClient":false,"BlokId":"57","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:15:00","EndDate":"2012-01-01 20:17:30"},{"Id":131,"ResourceId":1,"OtherClient":false,"BlokId":"58","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:30:00","EndDate":"2012-01-01 20:30:30"},{"Id":132,"ResourceId":1,"OtherClient":false,"BlokId":"59","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:45:00","EndDate":"2012-01-01 20:47:30"},{"Id":133,"ResourceId":1,"OtherClient":false,"BlokId":"60","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:59:30","EndDate":"2012-01-01 21:00:00"},{"Id":134,"ResourceId":1,"OtherClient":false,"BlokId":"61","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:15:00","EndDate":"2012-01-01 21:17:30"},{"Id":135,"ResourceId":1,"OtherClient":false,"BlokId":"62","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:30:00","EndDate":"2012-01-01 21:30:30"},{"Id":136,"ResourceId":1,"OtherClient":false,"BlokId":"63","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:45:00","EndDate":"2012-01-01 21:47:30"},{"Id":137,"ResourceId":2,"OtherClient":false,"BlokId":"64","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:59:30","EndDate":"2012-01-01 22:00:00"},{"Id":138,"ResourceId":2,"OtherClient":false,"BlokId":"65","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:15:00","EndDate":"2012-01-01 22:17:30"},{"Id":139,"ResourceId":2,"OtherClient":false,"BlokId":"66","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:30:00","EndDate":"2012-01-01 22:30:30"},{"Id":140,"ResourceId":1,"OtherClient":false,"BlokId":"67","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:45:00","EndDate":"2012-01-01 22:47:30"},{"Id":141,"ResourceId":2,"OtherClient":false,"BlokId":"68","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:59:30","EndDate":"2012-01-01 23:00:00"},{"Id":142,"ResourceId":2,"OtherClient":false,"BlokId":"69","DatumBloka":"2015-05-21","Color":3,"StartDate":"2012-01-01 23:15:00","EndDate":"2012-01-01 23:17:30"},{"Id":143,"ResourceId":2,"OtherClient":false,"BlokId":"70","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:30:00","EndDate":"2012-01-01 23:30:30"},{"Id":144,"ResourceId":1,"OtherClient":false,"BlokId":"71","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:45:00","EndDate":"2012-01-01 23:47:30"}],"capmaignePrice2":343.2,"schedulerDates2":[{"Id":1,"Name":"2015-05-21","Frequency":6}],"schedulerCommercial2":[{"Id":1,"BlokId":"1","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 06:15:00","EndDate":"2012-01-01 06:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":2,"BlokId":"5","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 07:15:00","EndDate":"2012-01-01 07:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":3,"BlokId":"9","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 08:15:00","EndDate":"2012-01-01 08:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":4,"BlokId":"17","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 10:15:00","EndDate":"2012-01-01 10:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":5,"BlokId":"25","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 12:15:00","EndDate":"2012-01-01 12:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":6,"BlokId":"37","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 15:15:00","EndDate":"2012-01-01 15:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":7,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":8,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":9,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":10,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":11,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":12,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":13,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":14,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":15,"ResourceId":1,"OtherClient":false,"BlokId":"3","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:45:00","EndDate":"2012-01-01 06:47:30"},{"Id":16,"ResourceId":1,"OtherClient":false,"BlokId":"7","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:45:00","EndDate":"2012-01-01 07:47:30"},{"Id":17,"ResourceId":1,"OtherClient":false,"BlokId":"10","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:30:00","EndDate":"2012-01-01 08:30:30"},{"Id":18,"ResourceId":1,"OtherClient":false,"BlokId":"11","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:45:00","EndDate":"2012-01-01 08:47:30"},{"Id":19,"ResourceId":1,"OtherClient":false,"BlokId":"12","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:59:30","EndDate":"2012-01-01 09:00:00"},{"Id":20,"ResourceId":1,"OtherClient":false,"BlokId":"13","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30"},{"Id":21,"ResourceId":1,"OtherClient":false,"BlokId":"14","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:30:00","EndDate":"2012-01-01 09:30:30"},{"Id":22,"ResourceId":1,"OtherClient":false,"BlokId":"15","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:45:00","EndDate":"2012-01-01 09:47:30"},{"Id":23,"ResourceId":1,"OtherClient":false,"BlokId":"16","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:59:30","EndDate":"2012-01-01 10:00:00"},{"Id":24,"ResourceId":1,"OtherClient":false,"BlokId":"18","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:30:00","EndDate":"2012-01-01 10:30:30"},{"Id":25,"ResourceId":1,"OtherClient":false,"BlokId":"19","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:45:00","EndDate":"2012-01-01 10:47:30"},{"Id":26,"ResourceId":1,"OtherClient":false,"BlokId":"20","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:59:30","EndDate":"2012-01-01 11:00:00"},{"Id":27,"ResourceId":1,"OtherClient":false,"BlokId":"21","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30"},{"Id":28,"ResourceId":1,"OtherClient":false,"BlokId":"22","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:30:00","EndDate":"2012-01-01 11:30:30"},{"Id":29,"ResourceId":1,"OtherClient":false,"BlokId":"23","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:45:00","EndDate":"2012-01-01 11:47:30"},{"Id":30,"ResourceId":1,"OtherClient":false,"BlokId":"26","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:30:00","EndDate":"2012-01-01 12:30:30"},{"Id":31,"ResourceId":1,"OtherClient":false,"BlokId":"27","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30"},{"Id":32,"ResourceId":1,"OtherClient":false,"BlokId":"28","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:59:30","EndDate":"2012-01-01 13:00:00"},{"Id":33,"ResourceId":1,"OtherClient":false,"BlokId":"29","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:15:00","EndDate":"2012-01-01 13:17:30"},{"Id":34,"ResourceId":1,"OtherClient":false,"BlokId":"30","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:30:00","EndDate":"2012-01-01 13:30:30"},{"Id":35,"ResourceId":1,"OtherClient":false,"BlokId":"31","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30"},{"Id":36,"ResourceId":1,"OtherClient":false,"BlokId":"32","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:59:30","EndDate":"2012-01-01 14:00:00"},{"Id":37,"ResourceId":1,"OtherClient":false,"BlokId":"33","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30"},{"Id":38,"ResourceId":1,"OtherClient":false,"BlokId":"34","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:30:00","EndDate":"2012-01-01 14:30:30"},{"Id":39,"ResourceId":1,"OtherClient":false,"BlokId":"35","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:45:00","EndDate":"2012-01-01 14:47:30"},{"Id":40,"ResourceId":1,"OtherClient":false,"BlokId":"36","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:59:30","EndDate":"2012-01-01 15:00:00"},{"Id":41,"ResourceId":1,"OtherClient":false,"BlokId":"38","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:30:00","EndDate":"2012-01-01 15:30:30"},{"Id":42,"ResourceId":1,"OtherClient":false,"BlokId":"39","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:45:00","EndDate":"2012-01-01 15:47:30"},{"Id":43,"ResourceId":1,"OtherClient":false,"BlokId":"40","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:59:30","EndDate":"2012-01-01 16:00:00"},{"Id":44,"ResourceId":1,"OtherClient":false,"BlokId":"41","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:15:00","EndDate":"2012-01-01 16:17:30"},{"Id":45,"ResourceId":1,"OtherClient":false,"BlokId":"42","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:30:00","EndDate":"2012-01-01 16:30:30"},{"Id":46,"ResourceId":1,"OtherClient":false,"BlokId":"43","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:45:00","EndDate":"2012-01-01 16:47:30"},{"Id":47,"ResourceId":1,"OtherClient":false,"BlokId":"44","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:59:30","EndDate":"2012-01-01 17:00:00"},{"Id":48,"ResourceId":1,"OtherClient":false,"BlokId":"45","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30"},{"Id":49,"ResourceId":1,"OtherClient":false,"BlokId":"46","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:30:00","EndDate":"2012-01-01 17:30:30"},{"Id":50,"ResourceId":1,"OtherClient":false,"BlokId":"47","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:45:00","EndDate":"2012-01-01 17:47:30"},{"Id":51,"ResourceId":1,"OtherClient":false,"BlokId":"48","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:59:30","EndDate":"2012-01-01 18:00:00"},{"Id":52,"ResourceId":1,"OtherClient":false,"BlokId":"49","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30"},{"Id":53,"ResourceId":1,"OtherClient":false,"BlokId":"51","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:45:00","EndDate":"2012-01-01 18:47:30"},{"Id":54,"ResourceId":1,"OtherClient":false,"BlokId":"52","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:59:30","EndDate":"2012-01-01 19:00:00"},{"Id":55,"ResourceId":1,"OtherClient":false,"BlokId":"53","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:15:00","EndDate":"2012-01-01 19:17:30"},{"Id":56,"ResourceId":1,"OtherClient":false,"BlokId":"54","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:30:00","EndDate":"2012-01-01 19:30:30"},{"Id":57,"ResourceId":1,"OtherClient":false,"BlokId":"55","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:45:00","EndDate":"2012-01-01 19:47:30"},{"Id":58,"ResourceId":1,"OtherClient":false,"BlokId":"57","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:15:00","EndDate":"2012-01-01 20:17:30"},{"Id":59,"ResourceId":1,"OtherClient":false,"BlokId":"58","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:30:00","EndDate":"2012-01-01 20:30:30"},{"Id":60,"ResourceId":1,"OtherClient":false,"BlokId":"59","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:45:00","EndDate":"2012-01-01 20:47:30"},{"Id":61,"ResourceId":1,"OtherClient":false,"BlokId":"60","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:59:30","EndDate":"2012-01-01 21:00:00"},{"Id":62,"ResourceId":1,"OtherClient":false,"BlokId":"61","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:15:00","EndDate":"2012-01-01 21:17:30"},{"Id":63,"ResourceId":1,"OtherClient":false,"BlokId":"62","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:30:00","EndDate":"2012-01-01 21:30:30"},{"Id":64,"ResourceId":1,"OtherClient":false,"BlokId":"63","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:45:00","EndDate":"2012-01-01 21:47:30"},{"Id":65,"ResourceId":1,"OtherClient":false,"BlokId":"64","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:59:30","EndDate":"2012-01-01 22:00:00"},{"Id":66,"ResourceId":1,"OtherClient":false,"BlokId":"65","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:15:00","EndDate":"2012-01-01 22:17:30"},{"Id":67,"ResourceId":1,"OtherClient":false,"BlokId":"66","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:30:00","EndDate":"2012-01-01 22:30:30"},{"Id":68,"ResourceId":1,"OtherClient":false,"BlokId":"67","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:45:00","EndDate":"2012-01-01 22:47:30"},{"Id":69,"ResourceId":1,"OtherClient":false,"BlokId":"68","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:59:30","EndDate":"2012-01-01 23:00:00"},{"Id":70,"ResourceId":1,"OtherClient":false,"BlokId":"69","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:15:00","EndDate":"2012-01-01 23:17:30"},{"Id":71,"ResourceId":1,"OtherClient":false,"BlokId":"70","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:30:00","EndDate":"2012-01-01 23:30:30"},{"Id":72,"ResourceId":1,"OtherClient":false,"BlokId":"71","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:45:00","EndDate":"2012-01-01 23:47:30"},{"Id":73,"BlokId":"13","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30","ResourceId":2,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":74,"BlokId":"21","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30","ResourceId":2,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":75,"BlokId":"31","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":76,"BlokId":"33","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":77,"BlokId":"45","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":78,"BlokId":"49","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":79,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":80,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":81,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":82,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":83,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":84,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":85,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":86,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":87,"ResourceId":1,"OtherClient":false,"BlokId":"1","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:15:00","EndDate":"2012-01-01 06:17:30"},{"Id":88,"ResourceId":1,"OtherClient":false,"BlokId":"3","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:45:00","EndDate":"2012-01-01 06:47:30"},{"Id":89,"ResourceId":1,"OtherClient":false,"BlokId":"5","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:15:00","EndDate":"2012-01-01 07:17:30"},{"Id":90,"ResourceId":1,"OtherClient":false,"BlokId":"7","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:45:00","EndDate":"2012-01-01 07:47:30"},{"Id":91,"ResourceId":1,"OtherClient":false,"BlokId":"9","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:15:00","EndDate":"2012-01-01 08:17:30"},{"Id":92,"ResourceId":1,"OtherClient":false,"BlokId":"10","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:30:00","EndDate":"2012-01-01 08:30:30"},{"Id":93,"ResourceId":1,"OtherClient":false,"BlokId":"11","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:45:00","EndDate":"2012-01-01 08:47:30"},{"Id":94,"ResourceId":1,"OtherClient":false,"BlokId":"12","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:59:30","EndDate":"2012-01-01 09:00:00"},{"Id":95,"ResourceId":1,"OtherClient":false,"BlokId":"14","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:30:00","EndDate":"2012-01-01 09:30:30"},{"Id":96,"ResourceId":1,"OtherClient":false,"BlokId":"15","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:45:00","EndDate":"2012-01-01 09:47:30"},{"Id":97,"ResourceId":1,"OtherClient":false,"BlokId":"16","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:59:30","EndDate":"2012-01-01 10:00:00"},{"Id":98,"ResourceId":1,"OtherClient":false,"BlokId":"17","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:15:00","EndDate":"2012-01-01 10:17:30"},{"Id":99,"ResourceId":1,"OtherClient":false,"BlokId":"18","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:30:00","EndDate":"2012-01-01 10:30:30"},{"Id":100,"ResourceId":1,"OtherClient":false,"BlokId":"19","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:45:00","EndDate":"2012-01-01 10:47:30"},{"Id":101,"ResourceId":1,"OtherClient":false,"BlokId":"20","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:59:30","EndDate":"2012-01-01 11:00:00"},{"Id":102,"ResourceId":1,"OtherClient":false,"BlokId":"22","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:30:00","EndDate":"2012-01-01 11:30:30"},{"Id":103,"ResourceId":1,"OtherClient":false,"BlokId":"23","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:45:00","EndDate":"2012-01-01 11:47:30"},{"Id":104,"ResourceId":1,"OtherClient":false,"BlokId":"25","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:15:00","EndDate":"2012-01-01 12:17:30"},{"Id":105,"ResourceId":1,"OtherClient":false,"BlokId":"26","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:30:00","EndDate":"2012-01-01 12:30:30"},{"Id":106,"ResourceId":1,"OtherClient":false,"BlokId":"27","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30"},{"Id":107,"ResourceId":1,"OtherClient":false,"BlokId":"28","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:59:30","EndDate":"2012-01-01 13:00:00"},{"Id":108,"ResourceId":1,"OtherClient":false,"BlokId":"29","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:15:00","EndDate":"2012-01-01 13:17:30"},{"Id":109,"ResourceId":1,"OtherClient":false,"BlokId":"30","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:30:00","EndDate":"2012-01-01 13:30:30"},{"Id":110,"ResourceId":1,"OtherClient":false,"BlokId":"32","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:59:30","EndDate":"2012-01-01 14:00:00"},{"Id":111,"ResourceId":1,"OtherClient":false,"BlokId":"34","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:30:00","EndDate":"2012-01-01 14:30:30"},{"Id":112,"ResourceId":1,"OtherClient":false,"BlokId":"35","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:45:00","EndDate":"2012-01-01 14:47:30"},{"Id":113,"ResourceId":1,"OtherClient":false,"BlokId":"36","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:59:30","EndDate":"2012-01-01 15:00:00"},{"Id":114,"ResourceId":1,"OtherClient":false,"BlokId":"37","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:15:00","EndDate":"2012-01-01 15:17:30"},{"Id":115,"ResourceId":1,"OtherClient":false,"BlokId":"38","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:30:00","EndDate":"2012-01-01 15:30:30"},{"Id":116,"ResourceId":1,"OtherClient":false,"BlokId":"39","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:45:00","EndDate":"2012-01-01 15:47:30"},{"Id":117,"ResourceId":1,"OtherClient":false,"BlokId":"40","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:59:30","EndDate":"2012-01-01 16:00:00"},{"Id":118,"ResourceId":1,"OtherClient":false,"BlokId":"41","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:15:00","EndDate":"2012-01-01 16:17:30"},{"Id":119,"ResourceId":1,"OtherClient":false,"BlokId":"42","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:30:00","EndDate":"2012-01-01 16:30:30"},{"Id":120,"ResourceId":1,"OtherClient":false,"BlokId":"43","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:45:00","EndDate":"2012-01-01 16:47:30"},{"Id":121,"ResourceId":1,"OtherClient":false,"BlokId":"44","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:59:30","EndDate":"2012-01-01 17:00:00"},{"Id":122,"ResourceId":1,"OtherClient":false,"BlokId":"46","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:30:00","EndDate":"2012-01-01 17:30:30"},{"Id":123,"ResourceId":1,"OtherClient":false,"BlokId":"47","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:45:00","EndDate":"2012-01-01 17:47:30"},{"Id":124,"ResourceId":1,"OtherClient":false,"BlokId":"48","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:59:30","EndDate":"2012-01-01 18:00:00"},{"Id":125,"ResourceId":1,"OtherClient":false,"BlokId":"51","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:45:00","EndDate":"2012-01-01 18:47:30"},{"Id":126,"ResourceId":1,"OtherClient":false,"BlokId":"52","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:59:30","EndDate":"2012-01-01 19:00:00"},{"Id":127,"ResourceId":1,"OtherClient":false,"BlokId":"53","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:15:00","EndDate":"2012-01-01 19:17:30"},{"Id":128,"ResourceId":1,"OtherClient":false,"BlokId":"54","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:30:00","EndDate":"2012-01-01 19:30:30"},{"Id":129,"ResourceId":1,"OtherClient":false,"BlokId":"55","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:45:00","EndDate":"2012-01-01 19:47:30"},{"Id":130,"ResourceId":1,"OtherClient":false,"BlokId":"57","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:15:00","EndDate":"2012-01-01 20:17:30"},{"Id":131,"ResourceId":1,"OtherClient":false,"BlokId":"58","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:30:00","EndDate":"2012-01-01 20:30:30"},{"Id":132,"ResourceId":1,"OtherClient":false,"BlokId":"59","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:45:00","EndDate":"2012-01-01 20:47:30"},{"Id":133,"ResourceId":1,"OtherClient":false,"BlokId":"60","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:59:30","EndDate":"2012-01-01 21:00:00"},{"Id":134,"ResourceId":1,"OtherClient":false,"BlokId":"61","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:15:00","EndDate":"2012-01-01 21:17:30"},{"Id":135,"ResourceId":1,"OtherClient":false,"BlokId":"62","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:30:00","EndDate":"2012-01-01 21:30:30"},{"Id":136,"ResourceId":1,"OtherClient":false,"BlokId":"63","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:45:00","EndDate":"2012-01-01 21:47:30"},{"Id":137,"ResourceId":1,"OtherClient":false,"BlokId":"64","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:59:30","EndDate":"2012-01-01 22:00:00"},{"Id":138,"ResourceId":1,"OtherClient":false,"BlokId":"65","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:15:00","EndDate":"2012-01-01 22:17:30"},{"Id":139,"ResourceId":1,"OtherClient":false,"BlokId":"66","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:30:00","EndDate":"2012-01-01 22:30:30"},{"Id":140,"ResourceId":1,"OtherClient":false,"BlokId":"67","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:45:00","EndDate":"2012-01-01 22:47:30"},{"Id":141,"ResourceId":1,"OtherClient":false,"BlokId":"68","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:59:30","EndDate":"2012-01-01 23:00:00"},{"Id":142,"ResourceId":1,"OtherClient":false,"BlokId":"69","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:15:00","EndDate":"2012-01-01 23:17:30"},{"Id":143,"ResourceId":1,"OtherClient":false,"BlokId":"70","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:30:00","EndDate":"2012-01-01 23:30:30"},{"Id":144,"ResourceId":1,"OtherClient":false,"BlokId":"71","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:45:00","EndDate":"2012-01-01 23:47:30"}]}}';















/*

    {
        "Id":1,
    "BlokId":"1",
    "DatumBloka":"2015-05-28",
    "Title":"naziv kamp",
    "StartDate":"2012-01-01 06:15:00",
    "EndDate":"2012-01-01 06:17:30",
    "ResourceId":1,
    "Duration":"150",
    "OtherClient":false,
    "CommercialBlockOrderID":"1",
    "Color":1,
    "SpotName":"spottt1",
    "SpotID":2075
    },

    {"Id":2,"BlokId":"5","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 07:15:00","EndDate":"2012-01-01 07:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":3,"BlokId":"9","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 08:15:00","EndDate":"2012-01-01 08:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":4,"BlokId":"17","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 10:15:00","EndDate":"2012-01-01 10:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":5,"BlokId":"25","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 12:15:00","EndDate":"2012-01-01 12:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":6,"BlokId":"37","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 15:15:00","EndDate":"2012-01-01 15:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":7,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":8,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":9,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":10,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":11,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":12,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":13,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":14,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":1,"SpotName":"spottt1","SpotID":2075},{"Id":15,"ResourceId":1,"OtherClient":false,"BlokId":"3","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 06:45:00","EndDate":"2012-01-01 06:47:30"},{"Id":16,"ResourceId":1,"OtherClient":false,"BlokId":"7","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 07:45:00","EndDate":"2012-01-01 07:47:30"},{"Id":17,"ResourceId":1,"OtherClient":false,"BlokId":"10","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:30:00","EndDate":"2012-01-01 08:30:30"},{"Id":18,"ResourceId":1,"OtherClient":false,"BlokId":"11","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:45:00","EndDate":"2012-01-01 08:47:30"},{"Id":19,"ResourceId":1,"OtherClient":false,"BlokId":"12","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 08:59:30","EndDate":"2012-01-01 09:00:00"},{"Id":20,"ResourceId":1,"OtherClient":false,"BlokId":"13","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30"},{"Id":21,"ResourceId":1,"OtherClient":false,"BlokId":"14","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:30:00","EndDate":"2012-01-01 09:30:30"},{"Id":22,"ResourceId":1,"OtherClient":false,"BlokId":"15","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:45:00","EndDate":"2012-01-01 09:47:30"},{"Id":23,"ResourceId":1,"OtherClient":false,"BlokId":"16","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 09:59:30","EndDate":"2012-01-01 10:00:00"},{"Id":24,"ResourceId":1,"OtherClient":false,"BlokId":"18","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:30:00","EndDate":"2012-01-01 10:30:30"},{"Id":25,"ResourceId":1,"OtherClient":false,"BlokId":"19","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:45:00","EndDate":"2012-01-01 10:47:30"},{"Id":26,"ResourceId":1,"OtherClient":false,"BlokId":"20","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 10:59:30","EndDate":"2012-01-01 11:00:00"},{"Id":27,"ResourceId":1,"OtherClient":false,"BlokId":"21","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30"},{"Id":28,"ResourceId":1,"OtherClient":false,"BlokId":"22","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:30:00","EndDate":"2012-01-01 11:30:30"},{"Id":29,"ResourceId":1,"OtherClient":false,"BlokId":"23","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 11:45:00","EndDate":"2012-01-01 11:47:30"},{"Id":30,"ResourceId":1,"OtherClient":false,"BlokId":"26","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:30:00","EndDate":"2012-01-01 12:30:30"},{"Id":31,"ResourceId":1,"OtherClient":false,"BlokId":"27","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30"},{"Id":32,"ResourceId":1,"OtherClient":false,"BlokId":"28","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 12:59:30","EndDate":"2012-01-01 13:00:00"},{"Id":33,"ResourceId":1,"OtherClient":false,"BlokId":"29","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:15:00","EndDate":"2012-01-01 13:17:30"},{"Id":34,"ResourceId":1,"OtherClient":false,"BlokId":"30","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:30:00","EndDate":"2012-01-01 13:30:30"},{"Id":35,"ResourceId":1,"OtherClient":false,"BlokId":"31","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30"},{"Id":36,"ResourceId":1,"OtherClient":false,"BlokId":"32","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 13:59:30","EndDate":"2012-01-01 14:00:00"},{"Id":37,"ResourceId":1,"OtherClient":false,"BlokId":"33","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30"},{"Id":38,"ResourceId":1,"OtherClient":false,"BlokId":"34","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:30:00","EndDate":"2012-01-01 14:30:30"},{"Id":39,"ResourceId":1,"OtherClient":false,"BlokId":"35","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:45:00","EndDate":"2012-01-01 14:47:30"},{"Id":40,"ResourceId":1,"OtherClient":false,"BlokId":"36","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 14:59:30","EndDate":"2012-01-01 15:00:00"},{"Id":41,"ResourceId":1,"OtherClient":false,"BlokId":"38","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:30:00","EndDate":"2012-01-01 15:30:30"},{"Id":42,"ResourceId":1,"OtherClient":false,"BlokId":"39","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:45:00","EndDate":"2012-01-01 15:47:30"},{"Id":43,"ResourceId":1,"OtherClient":false,"BlokId":"40","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 15:59:30","EndDate":"2012-01-01 16:00:00"},{"Id":44,"ResourceId":1,"OtherClient":false,"BlokId":"41","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:15:00","EndDate":"2012-01-01 16:17:30"},{"Id":45,"ResourceId":1,"OtherClient":false,"BlokId":"42","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:30:00","EndDate":"2012-01-01 16:30:30"},{"Id":46,"ResourceId":1,"OtherClient":false,"BlokId":"43","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:45:00","EndDate":"2012-01-01 16:47:30"},{"Id":47,"ResourceId":1,"OtherClient":false,"BlokId":"44","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 16:59:30","EndDate":"2012-01-01 17:00:00"},{"Id":48,"ResourceId":1,"OtherClient":false,"BlokId":"45","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30"},{"Id":49,"ResourceId":1,"OtherClient":false,"BlokId":"46","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:30:00","EndDate":"2012-01-01 17:30:30"},{"Id":50,"ResourceId":1,"OtherClient":false,"BlokId":"47","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:45:00","EndDate":"2012-01-01 17:47:30"},{"Id":51,"ResourceId":1,"OtherClient":false,"BlokId":"48","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 17:59:30","EndDate":"2012-01-01 18:00:00"},{"Id":52,"ResourceId":1,"OtherClient":false,"BlokId":"49","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30"},{"Id":53,"ResourceId":1,"OtherClient":false,"BlokId":"51","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:45:00","EndDate":"2012-01-01 18:47:30"},{"Id":54,"ResourceId":1,"OtherClient":false,"BlokId":"52","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 18:59:30","EndDate":"2012-01-01 19:00:00"},{"Id":55,"ResourceId":1,"OtherClient":false,"BlokId":"53","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:15:00","EndDate":"2012-01-01 19:17:30"},{"Id":56,"ResourceId":1,"OtherClient":false,"BlokId":"54","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:30:00","EndDate":"2012-01-01 19:30:30"},{"Id":57,"ResourceId":1,"OtherClient":false,"BlokId":"55","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 19:45:00","EndDate":"2012-01-01 19:47:30"},{"Id":58,"ResourceId":1,"OtherClient":false,"BlokId":"57","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:15:00","EndDate":"2012-01-01 20:17:30"},{"Id":59,"ResourceId":1,"OtherClient":false,"BlokId":"58","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:30:00","EndDate":"2012-01-01 20:30:30"},{"Id":60,"ResourceId":1,"OtherClient":false,"BlokId":"59","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:45:00","EndDate":"2012-01-01 20:47:30"},{"Id":61,"ResourceId":1,"OtherClient":false,"BlokId":"60","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 20:59:30","EndDate":"2012-01-01 21:00:00"},{"Id":62,"ResourceId":1,"OtherClient":false,"BlokId":"61","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:15:00","EndDate":"2012-01-01 21:17:30"},{"Id":63,"ResourceId":1,"OtherClient":false,"BlokId":"62","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:30:00","EndDate":"2012-01-01 21:30:30"},{"Id":64,"ResourceId":1,"OtherClient":false,"BlokId":"63","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:45:00","EndDate":"2012-01-01 21:47:30"},{"Id":65,"ResourceId":1,"OtherClient":false,"BlokId":"64","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 21:59:30","EndDate":"2012-01-01 22:00:00"},{"Id":66,"ResourceId":1,"OtherClient":false,"BlokId":"65","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:15:00","EndDate":"2012-01-01 22:17:30"},{"Id":67,"ResourceId":1,"OtherClient":false,"BlokId":"66","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:30:00","EndDate":"2012-01-01 22:30:30"},{"Id":68,"ResourceId":1,"OtherClient":false,"BlokId":"67","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:45:00","EndDate":"2012-01-01 22:47:30"},{"Id":69,"ResourceId":1,"OtherClient":false,"BlokId":"68","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 22:59:30","EndDate":"2012-01-01 23:00:00"},{"Id":70,"ResourceId":1,"OtherClient":false,"BlokId":"69","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:15:00","EndDate":"2012-01-01 23:17:30"},{"Id":71,"ResourceId":1,"OtherClient":false,"BlokId":"70","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:30:00","EndDate":"2012-01-01 23:30:30"},{"Id":72,"ResourceId":1,"OtherClient":false,"BlokId":"71","DatumBloka":"2015-05-21","Color":0,"StartDate":"2012-01-01 23:45:00","EndDate":"2012-01-01 23:47:30"},{"Id":73,"BlokId":"13","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 09:15:00","EndDate":"2012-01-01 09:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":74,"BlokId":"21","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 11:15:00","EndDate":"2012-01-01 11:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":75,"BlokId":"31","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 13:45:00","EndDate":"2012-01-01 13:47:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":76,"BlokId":"33","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 14:15:00","EndDate":"2012-01-01 14:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":77,"BlokId":"45","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 17:15:00","EndDate":"2012-01-01 17:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":78,"BlokId":"49","DatumBloka":"2015-05-21","Title":"naziv kamp","StartDate":"2012-01-01 18:15:00","EndDate":"2012-01-01 18:17:30","ResourceId":1,"Duration":"150","OtherClient":false,"CommercialBlockOrderID":"1","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":79,"BlokId":"2","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:30:00","EndDate":"2012-01-01 06:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":80,"BlokId":"4","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 06:59:30","EndDate":"2012-01-01 07:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":81,"BlokId":"6","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:30:00","EndDate":"2012-01-01 07:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":82,"BlokId":"8","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 07:59:30","EndDate":"2012-01-01 08:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":83,"BlokId":"24","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 11:59:30","EndDate":"2012-01-01 12:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":84,"BlokId":"50","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 18:30:00","EndDate":"2012-01-01 18:30:30","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":85,"BlokId":"56","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 19:59:30","EndDate":"2012-01-01 20:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},{"Id":86,"BlokId":"72","DatumBloka":"2015-05-21","Title":"Blok zauzet drugim reklamama","StartDate":"2012-01-01 23:59:30","EndDate":"2012-01-01 24:00:00","ResourceId":1,"Duration":"30","OtherClient":true,"CommercialBlockOrderID":"","Color":2,"SpotName":"spottt2","SpotID":2077},

    {
        "Id":87,
    "ResourceId":1,
    "OtherClient":false,
    "BlokId":"1",
    "DatumBloka":"2015-05-21",
    "Color":2,
    "StartDate":"2012-01-01 06:15:00",
    "EndDate":"2012-01-01 06:17:30"
    },
    */


















    return $data;

    return json_encode($data);

}








    public function GetTrajanjeNajduzegSpota($spotArray) {
        $trajanje = 0;
        foreach ($spotArray as $spot) {
            if ($spot->getSpotTrajanje() > $trajanje) {
                $trajanje = $spot->getSpotTrajanje();
            }
        }

        return $trajanje;
    }

    public function GetDelatnost() {
        $dbBroker = new CoreDBBroker();
        if (isset($this->brendID) && !empty($this->brendID)) {
            $query = "SELECT DelatnostID FROM brend WHERE BrendID = $this->brendID";
        } else {
            $query = "SELECT DelatnostID FROM klijent WHERE KlijentID = $this->klijentId";
        }
        $result = $dbBroker->selectOneRow($query);



/*
            send_respons_boban($query);
            exit;*/


        return $result['DelatnostID'];
    }




    public function VratiBlokoveUIstomSatu($blockId=0,$ista_vrsta_bloka=0) {//vraca blokove u istom satu iste vrste


        $blockId+=0;
        $ista_vrsta_bloka+=0;

        $blocks = array();
        $query = "select * from blok WHERE BlokID = $blockId";


        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        $sat = 0+$result['Sat'];
        $vrsta = 0+$result['Vrsta'];

        if($ista_vrsta_bloka) {
            $query = "select * from blok WHERE Sat = $sat AND Vrsta = $vrsta";
        }else{
            $query = "select * from blok WHERE Sat = $sat";
        }

        $rows = $dbBroker->selectManyRows($query);

        foreach ($rows['rows'] as $row) {
            $blockId_pom = 0+$row['BlokID'];
            $blocks[]=$blockId_pom;
        }

        return $blocks;
    }


    public function PopulateHours(){

        $query = "select BlokID, Sat from blok order by BlokID";

        $dbBroker = new CoreDBBroker();
        $rows = $dbBroker->selectManyRows($query);

        $hours=array();
        foreach ($rows['rows'] as $row) {
            $sat = $row['Sat'];
            $blokId = $row['BlokID'];
            $hours[$blokId]=$sat;
        }
        $this->hours=$hours;

    }







    public function makeBestOrder($arr5){


        foreach($arr5 as $date => $arr) {//petlja odredjeni datum



            $arr_new=array();


                //trazim setove koje treba permutovati
                $prvi_set_pocetak_i=0;
                $prvi_set_zavrsen = false;
                $drugi_set_pocetak_i=0;
                $drugi_set_zavrsen = false;
                $sat_old = 0;
                $sat_first=0;//sat posle koga se menjaju dva seta
                $i=0;
                foreach ($arr as $blok_pos => $price) {
                    $i++;
                    $blockId = substr($blok_pos, 0, strpos($blok_pos, '/'));
                    $sat = $this->hours[$blockId];

                    //echo $sat.'---'.$sat_old.'---'.$prvi_set_pocetak_i.'iiiiiiiiiiiiiiiii<br>';

                    if($prvi_set_zavrsen && !$drugi_set_zavrsen) {

                        if($drugi_set_pocetak_i != 0) {
                            if ($sat == $sat_old) {//trajanje drugog seta
                                $drugi_set_kraj_i = $i;
                                //$drugi_set_kraj_sat = $sat;
                            } else {
                                $drugi_set_zavrsen = true;
                            }
                        }


                        if (($sat - 1) != $sat_first && ($sat + 1) != $sat_first && $drugi_set_pocetak_i == 0) {//permutuj tekuci i sledeci
                            $drugi_set_pocetak_i = $i;
                            //$drugi_set_pocetak_sat = $sat;
                            $drugi_set_kraj_i = $i;
                            //$drugi_set_kraj_sat = $sat;
                        }
                    }


                    if(!$prvi_set_zavrsen) {
                        if($prvi_set_pocetak_i != 0) {
                            if ($sat == $sat_old) {//trajanje prvog seta
                                $prvi_set_kraj_i = $i;
                                //$prvi_set_kraj_sat = $sat;
                            } else {
                                $prvi_set_zavrsen = true;
                            }
                        }

                        //echo $sat.'---'.$sat_old.'---'.$prvi_set_pocetak_i.'<br>';



                        if ((($sat - 1) == $sat_old || ($sat + 1) == $sat_old) && $prvi_set_pocetak_i == 0) {//permutuj tekuci i sledeci

                            $sat_first = $sat_old;
                            $prvi_set_pocetak_i = $i;
                            //$prvi_set_pocetak_sat = $sat;
                            $prvi_set_kraj_i = $i;
                            //$prvi_set_kraj_sat = $sat;
                        }

                    }


                    $sat_old = $sat;
                }



                if($prvi_set_pocetak_i!=0 && $drugi_set_pocetak_i!=0){//imamo setove (znaci permutujemo)

                    //var_dump('changed order !!!');



                    $i=0;
                    foreach ($arr as $blok_pos => $price) {//pre prvog seta
                        $i++;
                        if($i<$prvi_set_pocetak_i){
                            $arr_new[$blok_pos]=$price;
                        }
                    }
                    $i=0;
                    foreach ($arr as $blok_pos => $price) {
                        $i++;
                        if($i>=$drugi_set_pocetak_i && $i<=$drugi_set_kraj_i){//drugi set
                            $arr_new[$blok_pos]=$price;
                        }
                    }
                    $i=0;
                    foreach ($arr as $blok_pos => $price) {
                        $i++;
                        if($i>$prvi_set_kraj_i && $i<$drugi_set_pocetak_i){//izmedju setova
                            $arr_new[$blok_pos]=$price;
                        }
                    }
                    $i=0;
                    foreach ($arr as $blok_pos => $price) {//prvi set
                        $i++;
                        if($i>=$prvi_set_pocetak_i && $i<=$prvi_set_kraj_i){//drugi set
                            $arr_new[$blok_pos]=$price;
                        }
                    }
                    $i=0;
                    foreach ($arr as $blok_pos => $price) {//posle drugog seta
                        $i++;
                        if($i>$drugi_set_kraj_i){
                            $arr_new[$blok_pos]=$price;
                        }
                    }


                    $arr5[$date] = $arr_new;

                }





        }

        return $arr5;

    }









    public function makeBestOrder_old($arr){

        foreach($arr as $date => $value){//petlja odredjeni datum

            $arrs=array();
            foreach($value as $blok_pos => $price) {
                $blockId = substr($blok_pos, 0, strpos($blok_pos, '/'));
                $sat=$this->hours[$blockId];
                $arrs[(string)$price][$sat][$blok_pos]=$price;
            }

/*
            var_dump($value);
            var_dump($arrs);
*/

            $arr[$date]=array();
            foreach($arrs as $price => $sat) {

                //var_dump($sat);
                //exit;


                ksort($sat);

                //var_dump($sat);

                $i=1;
                foreach($sat as $key_sat => $value_pom) {
                    if ($i%2==0) {//parni
                        //var_dump($sat."-".$key_sat);
                        $sat=move_element_of_array_by_key($sat,$key_sat,+10);
                    }
                    $i++;
                }
                /*var_dump($sat);
                exit;*/
                foreach($sat as $key_sat => $value_pom) {
                    $arr[$date]=$arr[$date]+$value_pom;
                }
            }

        }
        return $arr;
    }





}//end class

/*
function cmp($key_a, $key_b)
{
    $blockId_a = substr($key_a, 0, strpos($key_a, '/'));
    $blockId_b = substr($key_b, 0, strpos($key_b, '/'));

    if($blockId_a<$blockId_b){
        return -1;
    }else{
        return +1;
    }
}*/






?>
