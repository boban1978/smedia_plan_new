<?php

class CronClass {

    public function cron_reklame_all($radio="",$datum='',$debug=1) {


        if(isset($radio)){
            $radio=strtoupper($radio);
            $arr_radios=array();
            $arr_radios[]=$radio;
        }else{
			
		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);			
			
//            $arr_radios=array("s-juzni", "s-mix");
        }

        if ($datum != "") {
            $datum = date("Y-m-d", strtotime($datum));
        } else {
            $datum = date("Y-m-d", time());
        }

        $datum_start=$datum;

        $con = "";
        foreach ($arr_radios as $radio) {
                $con .= '<div style="font-size:30px;margin-top:30px;">Radio stanica: ' . $radio . "</div>";


		$switchclass = new SwitchClass();
		$radios_id = $switchclass->GetSwitchID($radio);
		unset($switchclass);

 /*               switch ($radio) {
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
                $dbBroker = new CoreDBBroker();

                $xml_path = __DIR__ . '/../../XML/' . $radio . '/file.xml';

                if (!file_exists($xml_path)) {
                    die('XML not exist!!!');
                }

                $playList = new PlayListClass($xml_path, $radio);
                $elements = $playList->Get('elements');

          
                /*
                        foreach ($elements as $key => $value) {
                            //${$key} = $value;
                            echo "$key = $value <br />";
                        }*/


                $path_root = "/home/SHARING/STREAMING/Reklame Zara/" . $radio . "/";
				//echo $path_root;


                    $datum = date("Y-m-d", strtotime($datum_start));
                  //  $folder = "Reklame Zara";

                    /***************************BRISANJE PREMIUMA SADRZAJA iz FOLDERA*****************************************/
                    $path_folder = $path_root;
                    $dir = dir($path_folder);
                    while (false !== ($file = $dir->read())) {
                        if (('.' == $file) || ('..' == $file) || (is_dir('./' . $file)) || ($file == "index.php") || ($file == "index.html"))
                            continue;

                        if (file_exists($path_folder . $file)) {

                            unlink($path_folder . $file);   ///ovde //

                        }
                    }

                    /***************************BRISANJE SADRZAJA iz FOLDERA*****************************************/







                    $query = "
select x.KampanjaNaziv, x.SpotNaziv, x.SpotTrajanje,x.Sat,x.Vrsta,x.RedniBroj
from (select
kb.Datum,
kb.Redosled,
b.Sat,
b.Vrsta,
k.Naziv as KampanjaNaziv,
k.RadioStanicaID,
k.StatusKampanjaID,
s.SpotName as SpotNaziv,
s.SpotTrajanje,
case
    when b.RedniBrojSat in (1,2) then 1
    when b.RedniBrojSat in (3,4)  then 2
end as RedniBroj
from kampanja k
left join kampanjablok kb on k.KampanjaID = kb.KampanjaID
left join spot s on kb.SpotID = s.SpotID
left join blok b on kb.BlokID = b.BlokID ) as x
where x.Datum = '$datum'
and x.RadioStanicaID = $radios_id
and x.StatusKampanjaID = 2
order by x.Redosled
";

                    $result = $dbBroker->selectManyRows($query);



                    $con .= '<div style="font-size:20px;margin-top:30px;">Datum: ' . $datum . "</div>";



                    for ($sat = 1; $sat < 25; $sat++) {

                        $blname = $this->get_blok_name($sat);
                        $con .= '<div style="font-size:18px;padding-left:20px;margin-top:20px;">SAT: ' . $blname . "</div>";
                        for ($vrsta = 0; $vrsta < 2; $vrsta++) {    ///// ovde
                        //$vrsta=0;

                            for ($rb = 1; $rb < 3; $rb++) {
                                $counter=0;

                                $con .= '<div style="font-size:16px;padding-left:40px;">' . (($vrsta == 0) ? 'Blok' : 'Premium') . " " . $rb ."</div>";
                                $data = "\n";
                                $data .= '[playlist]';

                                $search=array('file=','length=');
                                $replace=array('file'.$counter.'=','length'.$counter.'=');
                                $data_pom=$playList->get_pling_data();
                                $data_pom=str_replace($search,$replace,$data_pom);
                                $data .= $data_pom;


                                $rows = $result['rows'];
                                
                                $i = 0;
                                foreach ($rows as $row) {

                                    if($row['Sat']==$sat && $row['Vrsta']==$vrsta && $row['RedniBroj']==$rb) {

                                        $key = strtolower($row['SpotNaziv']);

                                        if (isset($elements[$key])) {
                                            $i++;
                                            $counter++;
                                            $con .= '<div style="font-size:14px;padding-left:60px;">Reklama ' . $i . ": " . $row['SpotNaziv'] . "</div>";

                                            $search=array('file=','length=');
                                            $replace=array('file'.$counter.'=','length'.$counter.'=');
                                          
                                            $data_pom=$elements[$key];

                                            $data_pom=str_replace($search,$replace,$data_pom);

                                            $data .= $data_pom;
                                        }

                                    }

                                }

                                $counter++;
                                $search=array('file=','length=');
                                $replace=array('file'.$counter.'=','length'.$counter.'=');
                                $data_pom=$playList->get_pling_data();
                                $data_pom=str_replace($search,$replace,$data_pom);
                                $data .= $data_pom;




                                $data .= "\n" . 'numberofentries='.($counter+1);
                                $data .= "\n" . 'nextindex=3';

                                //$fname = 'EPP_' . str_replace(" ", "", $blname) . '_' . ($vrsta == 1 ? 'Premium' : 'Blok') . '_' . $rb . '_(' . date("d.m.Y", strtotime($datum)) . ').fps.xml'; //fps.xml

                                //$fname = 'Blok_' .$rb.'_EPP_'. str_replace(" ", "", substr($blname,0,3)) .'.seq'; //fps.xml
								$fname = ($vrsta == 1 ? 'Premium_' : 'Blok_') .$rb.'_EPP_'. str_replace(" ", "", substr($blname,0,3)) .'.seq';

                                $path = $path_folder . $fname;

                                // The new person to add to the file
                                // Write the contents to the file,
                                // using the FILE_APPEND flag to append the content to the end of the file
                                // and the LOCK_EX flag to prevent anyone else writing to the file at the same time

                                if ($i > 0) {
                                    //echo $path;
                                    //echo $data;

                                }else{

                                    $data="";
                                }

                              /*  $data55 = file_get_contents($path);
                                //if($data!=$data55 || $data==''){//PISE SVE SEKVENCEEEEEEEEEEEEEEEEEEEEE
                              	  if($data!=$data55){
                                    if (file_put_contents($path, $data)) {

                                    }
                                }*/


									if($data){
                                    	if (file_put_contents($path, $data)) {

                                    }
                                  }

                            }//for sat
                        }//for sat   ////// ovde //
                    }//for sat



                unset($playList);


           // }    

        }



        if($debug) {
            echo $con;
        }




    }






    public function cron_reklame_check($radio="",$datum='') {

        $debug=1;



        if(isset($radio)){
            $radio=strtoupper($radio);

            $arr_radios=array();
            $arr_radios[]=$radio;
        }else{
			$switchclass = new SwitchClass();
			$arr_radios = $switchclass->GetArray();
			unset($switchclass);
        }

        if ($datum != "") {
            $datum = date("Y-m-d", strtotime($datum));
        } else {
            $datum = date("Y-m-d", time());
        }

        $datum_start=$datum;

        $con = "";
        foreach ($arr_radios as $radio) {
                $con .= '<div style="font-size:30px;margin-top:30px;">Radio stanica: ' . $radio . "</div>";

				$switchclass = new SwitchClass();
				$radios_id = $switchclass->GetSwitchID($radio);
				unset($switchclass);

/*                switch ($radio) {
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

                $dbBroker = new CoreDBBroker();

                $xml_path = __DIR__ . '/../../XML/' . $radio . '/file.xml';

                //define(XML_PATH, __DIR__ . '/../../XML/' . $radio . '/file.xml');


                if (!file_exists($xml_path)) {
                    die('XML not exist!!!');
                }

                $playList = new PlayListClass($xml_path, $radio);
                $elements = $playList->Get('elements');
                /*
                        foreach ($elements as $key => $value) {
                            //${$key} = $value;
                            echo "$key = $value <br />";
                        }*/


                $path_root = "/home/SHARING/STREAMING/Reklame Zara/" . $radio . "/";



                $datum = date("Y-m-d", strtotime($datum_start));
                $folder = "Reklame Zara";

                /***************************BRISANJE PREMIUMA SADRZAJA iz FOLDERA*****************************************/
                $path_folder = $path_root;
                $dir = dir($path_folder);
                while (false !== ($file = $dir->read())) {
                    if (('.' == $file) || ('..' == $file) || (is_dir('./' . $file)) || ($file == "index.php") || ($file == "index.html"))
                        continue;

                    if (file_exists($path_folder . $file)) {

                        //unlink($path_folder . $file);

                    }
                }

                /***************************BRISANJE SADRZAJA iz FOLDERA*****************************************/







                $query = "
select x.KampanjaNaziv, x.SpotNaziv, x.SpotTrajanje,x.Sat,x.Vrsta,x.RedniBroj
from (select
kb.Datum,
kb.Redosled,
b.Sat,
b.Vrsta,
k.Naziv as KampanjaNaziv,
k.RadioStanicaID,
k.StatusKampanjaID,
s.SpotName as SpotNaziv,
s.SpotTrajanje,
case
    when b.RedniBrojSat in (1,2) then 1
    when b.RedniBrojSat in (3,4)  then 2
end as RedniBroj
from kampanja k
left join kampanjablok kb on k.KampanjaID = kb.KampanjaID
left join spot s on kb.SpotID = s.SpotID
left join blok b on kb.BlokID = b.BlokID ) as x
where x.Datum = '$datum'
and x.RadioStanicaID = $radios_id
and x.StatusKampanjaID = 2
order by x.Redosled
";

                $result = $dbBroker->selectManyRows($query);



                $con .= '<div style="font-size:20px;margin-top:30px;">Datum: ' . $datum . "</div>";



                for ($sat = 1; $sat < 25; $sat++) {

                    $blname = $this->get_blok_name($sat);
                    $con .= '<div style="font-size:18px;padding-left:20px;margin-top:20px;">SAT: ' . $blname . "</div>";
                    for ($vrsta = 0; $vrsta < 2; $vrsta++) {
                    //$vrsta=0;

                    for ($rb = 1; $rb < 3; $rb++) {
                        $counter=0;

                        $con .= '<div style="font-size:16px;padding-left:40px;">' . (($vrsta == 0) ? 'Blok' : 'Premium') . " " . $rb . "</div>";
                        $data = "\n";
                        $data .= '[playlist]';

                        $search=array('file=','length=');
                        $replace=array('file'.$counter.'=','length'.$counter.'=');
                        $data_pom=$playList->get_pling_data();
                        $data_pom=str_replace($search,$replace,$data_pom);
                        $data .= $data_pom;


                        $rows = $result['rows'];
                        $i = 0;
                        foreach ($rows as $row) {

                            if($row['Sat']==$sat && $row['Vrsta']==$vrsta && $row['RedniBroj']==$rb) {

                                $key = strtolower($row['SpotNaziv']);

                                if (isset($elements[$key])) {
                                    $i++;
                                    $counter++;
                                    $con .= '<div style="font-size:14px;padding-left:60px;">Reklama ' . $i . ": " . $row['SpotNaziv'] . "</div>";

                                    $search=array('file=','length=');
                                    $replace=array('file'.$counter.'=','length'.$counter.'=');
                                    $data_pom=$elements[$key];
                                    $data_pom=str_replace($search,$replace,$data_pom);

                                    $data .= $data_pom;
                                }

                            }

                        }

                        $counter++;
                        $search=array('file=','length=');
                        $replace=array('file'.$counter.'=','length'.$counter.'=');
                        $data_pom=$playList->get_pling_data();
                        $data_pom=str_replace($search,$replace,$data_pom);
                        $data .= $data_pom;




                        $data .= "\n" . 'numberofentries='.($counter+1);
                        $data .= "\n" . 'nextindex=3';

                        //$fname = 'EPP_' . str_replace(" ", "", $blname) . '_' . ($vrsta == 1 ? 'Premium' : 'Blok') . '_' . $rb . '_(' . date("d.m.Y", strtotime($datum)) . ').fps.xml'; //fps.xml

                        //$fname = 'Blok_' .$rb.'_EPP_'. str_replace(" ", "", substr($blname,0,3)) .'.seq'; //fps.xml
						$fname = ($vrsta == 1 ? 'Premium_' : 'Blok_') .$rb.'_EPP_'. str_replace(" ", "", substr($blname,0,3)) .'.seq';

                        $path = $path_folder . $fname;

                        if ($i > 0) {
                            //echo $path;
                            //echo $data;

                        }else{

                            $data="";
                        }

                        /*
                        $data55 = file_get_contents($path);
                        if($data!=$data55){
                            if (file_put_contents($path, $data)) {

                            }
                        }*/




                    }//for sat
                    }//for sat ////ovde //
                }//for sat



                unset($playList);


            //}

        }



        if($debug) {
            echo $con;
        }




    }


    function get_blok_name($sat){
        if($sat==24){
            return "24h-01h";
        }
        $sat1=substr("0".$sat, -2);
        $sat2=substr("0".$sat+1, -2);
        return $sat1."h-".$sat2."h";
    }





  
  
  
  
  

  
  
  
  
  
  
  
  
  
  
  
  
  
  



    public function cron_xml($radio="",$debug=1) {


        error_reporting(E_ALL);
        ini_set('display_errors', '1');




        //$MP3FileClass = new MP3FileClass();



        if(isset($radio)){
            $radio=strtoupper($radio);
			
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
			
            $arr_radios=array();
            $arr_radios[]=$radio;
        }else{
			
		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);


//        $arr_radios = array("s-juzni", "s-mix");
        }



        foreach ($arr_radios as $radio) {

            //if($radio=="PINGVIN") {
            $con = '<PlayList>';

            $path_folder = "/home/SHARING/STREAMING/Emitovanje/Reklame/" . $radio . "/";

            /***************************BRISANJE PREMIUMA SADRZAJA iz FOLDERA*****************************************/
            $dir = dir($path_folder);

            while (false !== ($file = $dir->read())) {
                if (('.' == $file) || ('..' == $file) || (is_dir('./' . $file)) || ($file == "index.php") || ($file == "index.html") || (substr($file,-4)!='.mp3' AND substr($file,-4)!='.wav') )
                    continue;

                if (file_exists($path_folder . $file)) {
                    //unlink($path_folder . $file);

                    //echo $file.'<br>';


                    $thisVideoFile = new ffmpeg_movie($path_folder . $file);

					
//                    var_dump($thisVideoFile);
                    $duration = $thisVideoFile->getDuration();

                    $duration=round($duration*1000);

                    /*
					echo $test."<br>";
                    $test2 = $thisVideoFile->getAudioSampleRate();
					echo $test2."<br>";
                    $test3 = $thisVideoFile->getAudioCodec();
					echo $test3."<br>";*/
                  
                  
                    //var_dump(extension_loaded('ffmpeg'));

                    $con .= '
<PlayItem>
<Naziv>' . $file . '</Naziv>
<Trajanje>' . $duration . '</Trajanje>
</PlayItem>';

                    unset($thisVideoFile);

                }
            }

            /***************************BRISANJE SADRZAJA iz FOLDERA*****************************************/


                /*****************PLING********************/

                $con .= '
</PlayList>';


                //$path=realpath("./radios/xml/file.xml");
                $path = "/var/www/html/mediastream/XML/" . $radio . "/file.xml";
                if (file_put_contents($path, $con, LOCK_EX)) {
                    if($debug) {
                        echo $radio . " &nbsp&nbsp&nbsp&nbspXML OK<br/>";
                    }

                }


            //}
        }







    }//end cron_xml




  
  



    public function cron_fakturisanje() {


        require_once(__DIR__.'/../../html2pdf_v4.03/html2pdf.class.php');
        require_once(__DIR__.'/../../pjmail/pjmail.class.php');
        ob_start();


        $mails_arr=array();
        //$mails_arr[]="d.markovic@smedia.rs";
        //$mails_arr[]="p.andjelkovic@smedia.rs";
        //$mails_arr[]="t.andjelkovic@as-media.rs";
        //$mails_arr[]="d.zanze@as-media.rs";

        $dbBroker = new CoreDBBroker();
        $datum=date("Y-m-d");
        //$datum="2015-10-10";
        $query = "SELECT * FROM kampanja WHERE FinansijskiStatusID < '2' AND DatumKraja < '$datum'";
        $result = $dbBroker->selectManyRows($query);
        $rows = $result['rows'];

        $kampanja= new Kampanja();
        $kampanja->setFinansijskiStatusID(2);

        $dbBroker->beginTransaction();

        $i = 0;
        $result=true;
        foreach ($rows as $row) {
            $i++;

            $condition = " KampanjaID = ".$row['KampanjaID'];
            $result = $result && $dbBroker->update($kampanja, $condition);

            $kampanjaPdfClass = new KampanjaPdfClass();
            $html = $kampanjaPdfClass->GetFakturaHTML($row['KampanjaID']);


            foreach($mails_arr as $mail_addr) {

                try {
                    $html2pdf = new HTML2PDF('P', 'A4', 'en', true, 'UTF-8', array(15, 5, 15, 5));
                    $html2pdf->pdf->SetDisplayMode('fullpage');
                    //$html2pdf->pdf->SetAutoPageBreak(TRUE, 30);
                    ob_get_clean();
                    //header('Content-Type: appliacation/JSON');
                    $html2pdf->writeHTML($html);

                    $content_PDF = $html2pdf->Output('', true);

                    $mail = new PJmail();
                    $mail->setAllFrom('office@smedia.rs', "Plan software");
                    $mail->addrecipient($mail_addr);
                    $mail->addsubject("Završena kampanja (" . $row['Naziv'] . ")");
                    $mail->text = "U prilogu se nalazi faktura za kampanju (" . $row['Naziv'] . ")";
                    $mail->addbinattachement("faktura.pdf", $content_PDF);
                    $res = $mail->sendmail();

                    //header('Content-Type: application/pdf');
                    //echo "{success: true}";
                } catch (HTML2PDF_exception $e) {
                    //echo "{success: false}";
                }

            }


        }

        if ($result) {
            $dbBroker->commit();
        }
        else {
            $dbBroker->rollback();
        }
        $dbBroker->close();


    }



    public function cron_set_kurs(){

        $kurs=0;

        $url="http://www.nbs.rs/kursnaListaModul/srednjiKurs.faces?lang=lat";
  
        // Create a new DOM Document to hold our webpage structure
        $xml = new DOMDocument();
 
        // Load the url's contents into the DOM
        $xml->loadHTMLFile($url);
     
        //Loop through each <a> tag in the dom and add it to the link array
        foreach($xml->getElementsByTagName('tbody') as $tbody) {
            
   
            $tbodies_id = $tbody->getAttribute('id');
            if($tbodies_id=="index:srednjiKursList:tbody_element"){
                $trs = $tbody->childNodes;
                foreach($trs as $tr) {
                    $tds = $tr->childNodes;
                    $i=0;
                    $TF=false;
                    foreach($tds as $td) {
                        $i++;
                        if($i==3){
                            if($td->nodeValue=="EUR"){
                                $TF=true;
                            }
                        }
                        if($i==5 && $TF){
                            $kurs=$td->nodeValue;
                            break(3);
                        }
                    }
                }

            }

        }


        if($kurs==0){
            echo "KURS ERROR!";
            exit;
        }

        $kurs=str_replace(",",".",$kurs);
        $kurs = floatval($kurs);


        $kurs_obj=NEW Kurs();
        $kurs_obj->setDatum(date("Y-m-d"));
        $kurs_obj->setVrednost($kurs);


        $dbBroker = new CoreDBBroker();

        $TF=$dbBroker->insert($kurs_obj);


        if(!$TF){
            $condition = " Datum = '" . $kurs_obj->getDatum()."'";
            $TF=$dbBroker->update($kurs_obj, $condition);
        }

        var_dump($TF);

    }


  
  
  
  
    /*PROVERA DUZINE TRAJANJE REKLAME*/
	public function provera_duzine_reklame($radio="",$debug=1){
		$datum = date('Y-m-d');
		$con = '';

		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);
		
			 foreach ($arr_radios as $radio) {
					if($radio=="RADIOS3_CG" || $radio=="RADIOS1_CG"){
						switch ($radio) {
							case "RADIOS1_CG":
								$radio = "RADIOS1_CG";
								$radios_id = 1;
								break;
							case "RADIOS3_CG":
								$radio = "RADIOS3_CG";
								$radios_id = 2;
								break;
							default:
								die('radio not set!!!');
						}
					}

			$arr1 = array();
			
			$conn = mysqli_connect("localhost","root","Qwerty123","smedia");

			// Check connection
			if (mysqli_connect_errno())
			  {
			  echo "Failed to connect to MySQL: " . mysqli_connect_error();
			  }
			  
			  $query = "SELECT
						kampanjaspot.SpotID,
						spot.SpotName
						FROM
						kampanja
						INNER JOIN kampanjaspot ON kampanjaspot.KampanjaID = kampanja.KampanjaID
						INNER JOIN spot ON kampanjaspot.SpotID = spot.SpotID
						INNER JOIN kampanjablok ON kampanjablok.SpotID = spot.SpotID
						WHERE
						kampanja.StatusKampanjaID = '2' AND
						kampanjablok.Datum = '$datum' AND
						kampanja.RadioStanicaID = '$radios_id'";
				
				$result = mysqli_query($conn,$query);
				
				$rows_num = mysqli_num_rows($result);
				
				if($rows_num > 0){
					while($row = mysqli_fetch_assoc($result)){
						$arr1[$row['SpotName']] = $row['SpotID'];
					}
				}
				
				
			/****************************************************************************************/
			$muzika_file3 = scandir("/home/SHARING/".$radio."/Emitovanje/Reklame/");
			/************************citanje foldera muzika2*****************************************/

			$arr0 = array();
			$i = 0;
			foreach($muzika_file3 as $m){
					$path_parts = pathinfo($m);
			if($path_parts['extension'] == 'mp3' || $path_parts['extension'] == 'wav'){
					$arr0[$i]['name'] = substr($path_parts['basename'], 0, -4);
					$data = shell_exec("ffmpeg -i '/home/SHARING/".$radio."/Emitovanje/Reklame/".$path_parts['basename'] ."' 2>&1");
			
					preg_match("/Duration: (\d{2}:\d{2}:\d{2}\.\d{2})/",$data,$matches);
					$time = explode(':',$matches[1]);
					$hour = $time[0];
					$minutes = $time[1];
					$seconds = round($time[2]);
					
					$total_seconds = 0;
					$total_seconds += 60 * 60 * $hour;
					$total_seconds += 60 * $minutes;
					$total_seconds += $seconds;
					
					//echo $total_seconds.'</br>';
					$arr0[$i]['time'] = intval($total_seconds);
					$i++;
				}
				
			}
			$con .= '<div style="font-size:30px;margin-top:30px;">Radio stanica: ' . $radio . "</div>";

			foreach($arr0 as $key0){

				if(array_key_exists($key0['name'], $arr1) ){
					$id = $arr1[$key0['name']];
				
					if($id){
						$query2 = "UPDATE `spot` SET `SpotTrajanje`='".$key0['time']."' WHERE (`SpotID`='$id')";
						$con .= '<div style="font-size:18px;padding-left:20px;margin-top:0px;">Reklama: ' . $key0['name'] . " " .$key0['time']. " sec</div>";
						mysqli_query($conn, $query2);
						$id = '';
					}
				}
			}
			
		}
		
		
           echo $con;
        
		echo 'OK';
		
	}
  
  /*KRAJ PROVERE DUZINE TRAJANJA REKLAME*/
  
  

}


?>
