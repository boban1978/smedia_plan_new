<?php

/**
 * Description of KampanjaClass
 *
 * @author n.lekic
 */
class PomocniAlatiClass {




    public function SporneReklame()
    {

		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);


//        $arr_radios = array("s-juzni", "s-mix");
                $datum = date('Y-m-d') . " 00:00:00";


                //$datum='2015-01-01';



                $commonClass = new CommonClass();

                $arr=array();
                foreach ($arr_radios as $radio) {

		$switchclass = new SwitchClass();
		$radios_id = $switchclass->GetSwitchID($radio);
		unset($switchclass);


/*
            switch ($radio) {
                case "s-juzni":
                    $radios_id = 1;
                    break;
                case "s-mix":
                    $radios_id = 2;
                    break;
                default:
                    die('radio not set!!!');
            }

*/




                    $xml_path=__DIR__ . '/../../XML/' . $radio . '/file.xml';

                    if (!file_exists($xml_path)) {
                        die('XML not exist!!!');
                    }

                    $playList = new PlayListClass($xml_path, $radio);
                    $elements = $playList->Get('elements');

                    /*
                    var_dump($elements);
                    exit;


                    foreach ($elements as $key => $value) {

                        echo "$key<br />";

                        //${$key} = $value;
                        //echo "$key = $value <br />";
                    }
                    exit;*/



                    $nizArr = $commonClass->getSpotsAutocomplete($radios_id,1);


                    $query = "SELECT
                    spot.SpotName,
                    spot.SpotID,
                    kampanja.Naziv as KampanjaName,
                    kampanja.DatumPocetka as DatumPocetka
                    FROM
                    kampanja
                    INNER JOIN kampanjaspot ON kampanja.KampanjaID = kampanjaspot.KampanjaID
                    INNER JOIN spot ON kampanjaspot.SpotID = spot.SpotID
                    WHERE
                    kampanja.DatumKraja >= '$datum' AND
                    kampanja.RadioStanicaID = $radios_id";

                    $dbBroker = new CoreDBBroker();
                    $result = $dbBroker->selectManyRows($query);

                    foreach ($result['rows'] as $row) {

                        $spotID = $row['SpotID'];
                        $spotName = $row['SpotName'];
                        $kampanjaName = $row['KampanjaName'];
                        $datumPocetka = date("d.m.Y",strtotime($row['DatumPocetka']));


                        if(!isset($elements[strtolower($spotName)])){

                            /*
                            echo $radios_id;
                            echo $spotName;
                            var_dump($elements);
                            exit;
*/


                            $arr[]=array("SpotName" => $spotName,
                                "SpotID" => $spotID,
                                "KampanjaName" => $kampanjaName,
                                "RadioStanica" => $radio,
                                "RadioStanicaID" => $radios_id,
                                "DatumPocetka" => $datumPocetka
                            );
                        }else if(!in_array($spotName,$nizArr)){
                            $arr[]=array("SpotName" => $spotName,
                                "SpotID" => $spotID,
                                "KampanjaName" => $kampanjaName,
                                "RadioStanica" => $radio,
                                "RadioStanicaID" => $radios_id,
                                "DatumPocetka" => $datumPocetka
                            );
                        }


/*
                        $arr[]=array("spotName" => $spotName,
                            "spotID" => $spotID,
                            "kampanjaName" => $kampanjaName,
                            "radioStanica" => $radio,
                            "radioStanicaID" => $radios_id
                        );
*/




                    }


                }

                $responseNew = new CoreAjaxResponseInfo();
                if ($result) {
                    $responseNew->SetSuccess('true');
                    $responseNew->SetData('rows:' . json_encode($arr));
                } else {
                    $responseNew->SetSuccess('false');
                    $responseNew->SetMessage(CoreError::getError());
                }
                return $responseNew;


    }





    public function VisakReklame($start=0,$limit=25,$sort=NULL,$dir=NULL,$page=NULL)
    {


		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);


//        $arr_radios = array("s-juzni", "s-mix");

        //$arr_radios = array("PINGVIN", "GRADSKI");


        $datum = date('Y-m-d') . " 00:00:00";


        //$datum='2015-01-01';

        $dbBroker = new CoreDBBroker();

        $commonClass = new CommonClass();

        $arr=array();

        $nizArr_real=array();
        foreach ($arr_radios as $radio) {

		$switchclass = new SwitchClass();
		$radios_id = $switchclass->GetSwitchID($radio);
		unset($switchclass);


/*
            switch ($radio) {
                case "s-juzni":
                    $radios_id = 1;
                    break;
                case "s-mix":
                    $radios_id = 2;
                    break;
                default:
                    die('radio not set!!!');
            }

*/

/*
            $xml_path=__DIR__ . '/../../XML/' . $radio . '/file.xml';

            if (!file_exists($xml_path)) {
                die('XML not exist!!!');
            }

            $playList = new PlayListClass($xml_path);
            $elements = $playList->Get('elements');
*/
            /*
            var_dump($elements);
            exit;


            foreach ($elements as $key => $value) {

                echo "$key<br />";

                //${$key} = $value;
                //echo "$key = $value <br />";
            }
            exit;*/



            $nizArr = $commonClass->getSpotsAutocomplete($radios_id,1);



            $query = "SELECT
                    spot.SpotName,
                    spot.SpotID,
                    kampanja.Naziv as KampanjaName,
                    kampanja.DatumKraja as DatumKraja
                    FROM
                    kampanja
                    INNER JOIN kampanjaspot ON kampanja.KampanjaID = kampanjaspot.KampanjaID
                    INNER JOIN spot ON kampanjaspot.SpotID = spot.SpotID
                    WHERE
                    kampanja.DatumKraja < '$datum' AND
                    kampanja.RadioStanicaID = $radios_id";


            $result_past = $dbBroker->selectManyRows($query);

            $niz_datum_kraja=array();

            foreach ($result_past['rows'] as $row) {
                $spotName = $row['SpotName'];
                $datumKraja = $row['DatumKraja'];
                //$nizArr_tekuce[] = $spotName;
                if(isset($nizArr[$spotName])) {
                    $niz_datum_kraja[$spotName]=$datumKraja;
                }
            }



            $query = "SELECT
                    spot.SpotName,
                    spot.SpotID,
                    kampanja.Naziv as KampanjaName/*,
                    kampanja.DatumKraja as DatumKraja*/
                    FROM
                    kampanja
                    INNER JOIN kampanjaspot ON kampanja.KampanjaID = kampanjaspot.KampanjaID
                    INNER JOIN spot ON kampanjaspot.SpotID = spot.SpotID
                    WHERE
                    kampanja.DatumKraja >= '$datum' AND
                    kampanja.RadioStanicaID = $radios_id";

            $result = $dbBroker->selectManyRows($query);

            //$nizArr_tekuce = array();


            foreach ($result['rows'] as $row) {
                $spotName = $row['SpotName'];
                //$datumKraja = date("d.m.Y",strtotime($row['DatumKraja']));
                //$nizArr_tekuce[] = $spotName;

                if(isset($nizArr[$spotName])) {
                    unset($nizArr[$spotName]);
                }

            }






            foreach ($nizArr as $key => $value) {

                if(isset($niz_datum_kraja[$value])){
                    $datumKraja=$niz_datum_kraja[$value];
                }else{
                    $datumKraja='--';
                }





                if($sort=="RadioStanica") {
                    $key_new=$radio . "###" . $value . "###" . $datumKraja . "###" . $radios_id;
                }else if($sort=="DatumKraja"){
                    $key_new=$datumKraja . "###" . $radio . "###" . $value . "###" . $radios_id;
                }else{
                    $key_new=$value . "###" . $radio . "###" . $datumKraja . "###" . $radios_id;
                }
                $key_new=strtolower($key_new);
                $nizArr_real[$key_new] = $value;

            }




        }

        if($dir=="DESC"){
            krsort($nizArr_real);
        }else{
            ksort($nizArr_real);
        }

        $i=0;
        $j=0;
        foreach ($nizArr_real as $key => $value) {




            $key_arr=explode("###",$key);


            if($sort=="RadioStanica") {
                $radio = $key_arr[0];
                $spotName = $value;
                $datumKraja = $key_arr[2];
                $radios_id = $key_arr[3];
            }else if($sort=="DatumKraja") {
                $datumKraja = $key_arr[0];
                $spotName = $value;
                $radio = $key_arr[1];
                $radios_id = $key_arr[3];
            }else{
                $spotName = $value;
                $radio = $key_arr[1];
                $datumKraja = $key_arr[2];
                $radios_id = $key_arr[3];
            }


            $i++;
            if($i>=$start) {
                $j++;

                if($j<=$limit) {

                    if($datumKraja!="--") {
                        $datumKraja = date("d.m.Y", strtotime($datumKraja));
                    }

                    $arr[] = array("SpotName" => $spotName,
                        "RadioStanica" => strtoupper($radio),
                        "DatumKraja" => $datumKraja,
                        "RadioStanicaID" => $radios_id
                    );
                }
            }



            /*
                                    $arr[]=array("spotName" => $spotName,
                                        "spotID" => $spotID,
                                        "kampanjaName" => $kampanjaName,
                                        "radioStanica" => $radio,
                                        "radioStanicaID" => $radios_id
                                    );
            */

        }





        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:' . json_encode($arr).', total:'.$i);//                               );



        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;


    }















    public function SporneReklame_xxx()
    {

        $datum = date('Y-m-d') . " 00:00:00";

            $query = "SELECT
                    spot.SpotName,
                    spot.SpotID,
                    kampanja.Naziv as KampanjaName
                    FROM
                    kampanja
                    INNER JOIN kampanjaspot ON kampanja.KampanjaID = kampanjaspot.KampanjaID
                    INNER JOIN spot ON kampanjaspot.SpotID = spot.SpotID
                    WHERE
                    kampanja.DatumKraja >= '$datum' AND
                    kampanja.RadioStanicaID = 4";

            $dbBroker = new CoreDBBroker();
            $result = $dbBroker->selectManyRows($query);

            foreach ($result['rows'] as $row) {

                $spotID = $row['SpotID'];
                $spotName = $row['SpotName'];
                $kampanjaName = $row['KampanjaName'];

                $radios_id = 4;
                $radio='PINGVIN';

                    $arr[]=array("SpotName" => $spotName,
                        "SpotID" => $spotID,
                        "KampanjaName" => $kampanjaName,
                        "RadioStanica" => $radio,
                        "RadioStanicaID" => $radios_id
                    );


            }




        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:' . json_encode($arr));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;


    }









}

?>
