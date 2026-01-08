<?php
class MediaPlanClass {
    // svi datumi koji predstavljaju ulazne parametre treba da budu u formatu '2010-10-01'
    public function GetDatesForCampaign($startDate, $endDate, $exludedDate = array()) {
        $return = array($startDate);
        $start = $startDate;
        $i=1;
        if (strtotime($startDate) < strtotime($endDate))
        {
            while (strtotime($start) < strtotime($endDate))
            {
                $start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                $return[] = $start;
                $i++;
            }
        }

        $result = array_diff($return, $exludedDate);

        return $result;
    }
    
    // funkcija koja dohvata sve slobodne blokove za svaki od datuma koji su dobijeni u funkciji, perioda i trajanja spota
    public function GetFreeBlockForDay($availableDays, $spotDuration, $periodOfDay = 0) {
	$daysWithFreeBlocks = array();
	foreach($availableDays as $availableDay) {
		/*
		$rows = UPIT;
		ovde ide upit koji vraca sve redove iz tabele blokzauzece
		za odredjeni datum $availableDay u kome su blokovi iz izabranog perioda dana i u kome je $spotDuration <= PreostaloSekundi
		*/
            
                $query = "select B.BlokID 
                            from blok B 
                            left outer join blokzauzece BZ on B.BlokID = BZ.BlokID and  BZ.Datum = $availableDay
                            where (B.Trajanje - ifnull(BZ.PreostaloSekundi, 0)) > $spotDuration ";
                switch ($periodOfDay) {
                    case 0:
                        break;
                    case 1:
                        //Prepodne
                        $query .= "and B.Sat < 12";
                        break;
                    case 2:
                        //Popodne
                        $query .= "and B.Sat between 12 and 17";
                        break;
                    case 3:
                        //Uvece
                        $query .= "and B.Sat >= 18";
                        break;
                    default:
                        break;
                }
                $dbBroker = new CoreDBBroker();
                $rows = $dbBroker->selectManyRows($query);
  		$blocks = array();
		foreach($rows as $row) {
			$blocks[] = $row['BlokID'];
		}
                
                if(!empty($blocks)) {
                    $daysWithFreeBlocks[$availableDay] = $blocks;
                }
	}
	
	return $daysWithFreeBlocks;
    }
    
    // funkcija za vracanje cene za odredjene blokove u toku dana
    public function GetPriceForBlocks($availableBlocks, $spotDuration, $discount = 0) {
	$priceForBlocks = array();
        // $rows = UPIT;
	// upit koji vadi sve redove iz tabele kategorijacena
	
//	foreach($rows as $row) {
//		if($row['TrajanjeOd'] <= $spotDuration && $row['TrajanjeDo'] >= $spotDuration) {
//			$kategorijaCenaId = $row['KategorijaCenaID'];
//			break;
//		}
//	}
	
	foreach($availableBlocks as $key=>$value) {
		$blocks = array();
		foreach($value as $block) {
			// $row = UPIT;
			// ovde treba da ide upit koji vraca jedan red iz tabele cenovnik na osnovu $block (to je BlokID) i $kategorijaCenaId
                        $query = "select C.Cena
                                    from cenovnik C
                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID 
                                    where $spotDuration between KC.TrajanjeOd and KC.TrajanjeDo AND C.BlokID = $block";
                                    // mislim da bi ovde u upitu trebalo da se doda na kraju: AND C.BlokID = $blocks
                        $dbBroker = new CoreDBBroker();
                        $row = $dbBroker->selectOneRow($query);
			$blocks[$block] = number_format($row['Cena'] - ($row['Cena']*$discount/100), 2);
		}
		$priceForBlocks[$key] = $blocks;
	}
        
        return $priceForBlocks;
    }
    
    // funcija za sortiranja blokova po ceni
    public function SortBlocksByPrice($priceForBlocks) {
            foreach($priceForBlocks as $key=>$value) {
                    uasort($value, function($a, $b){
                            if ($a == $b) {
                                    return 0;
                            }
                            return ($a < $b) ? -1 : 1;
                    });
                    $sortBlocks[$key] = $value;
            }
            return $sortBlocks;
    }
    
    // funkcija koja vraca dostupne dane za reklamiranje sa slobodnim blokovima sortiranim po ceni reklame
    public function GetAvailableDaysForCampaign($startDate, $endDate, $spotDuration, $periodOfDay = 0, $exludedDate = array()){
        $sortBlocksByPrice = array();
        $messages = array();
        $datesForCampaign = $this->GetDatesForCampaign($startDate, $endDate, $exludedDate);
        if(!empty($datesForCampaign)) {
            $daysWithFreeBlocks = $this->GetFreeBlockForDay($datesForCampaign, $spotDuration, $periodOfDay);
            if(!empty($daysWithFreeBlocks)) {
                $priceForBlocks = $this->GetPriceForBlocks($daysWithFreeBlocks, $spotDuration, $discount = 0);
                $sortBlocksByPrice = $this->SortBlocksByPrice($priceForBlocks);
                return $sortBlocksByPrice;
            } else {
                $messages['message'] = 'U izabranom periodu nema slobodnih termina za Vasu kampanju.';
                return $messages;
            }
        } else {
            $messages['message'] = 'Niste izabrali ni jedan datum za Vasu kampanju.';
            return $messages;
        }
    }
    
    public function MediaPlanLoad($startDate, $endDate, $radioStanicaID = 'null') {
        $dbBroker = new CoreDBBroker();
        $query = "SELECT bz.*, b.Sat, b.RedniBrojSat, b.Trajanje
                    FROM blokzauzece bz
                    INNER JOIN blok b
                    ON bz.BlokID = b.BlokID
                    WHERE (bz.Datum between '".$startDate."' and '".$endDate."') 
                    and ({$radioStanicaID} = null or bz.RadioStanicaID = {$radioStanicaID})
                    ORDER BY bz.BlokID";
        $result = $dbBroker->selectManyRows($query);
        $response = new stdClass();
        //$data = new stdClass();
        $i = 1;
        $evts = array();
        foreach ($result['rows'] as $row) {
            //echo $row['BlokID'].'<br>';
            // {"id":1 , "cid": 1 , "title":"Blok1 - Zauzeto 20/150 sec" , "start": "2012-05-21 08:00:00" , "end": "2012-05-21 08:30:00"},
            if(strlen(trim($row['Sat'])) < 2){
                $sat = '0'.trim($row['Sat']);
            } else {
                $sat = trim($row['Sat']);
            }
            $zauzeto = ($row['ZauzetoSekundi']/$row['Trajanje'])*100;
            if($zauzeto < 25){
                $cid = 1;
            } elseif($zauzeto > 75) {
                $cid = 3;
            } else {
                $cid = 2;
            } 
            $rowEvts['id'] = $i;
            $rowEvts['cid'] = $cid;
            $rowEvts['loc'] = $row['BlokID'];
            $rowEvts['title'] = "Blok ".$row['RedniBrojSat']." - Zauzeto ".$row['ZauzetoSekundi']."/".$row['Trajanje']." sec";
            $rowEvts['start'] = date("Y-m-d", strtotime($startDate))." ".$sat.":00:00";
            $rowEvts['end'] = date("Y-m-d", strtotime($endDate))." ".$sat.":30:00";
            $evts[] = $rowEvts;
            $i++;
        }        
                
        if($result) {
            
            $response->success = true;
            $response->evts = $evts;
        } else {
            $response->success = false;
            $response->msg = CoreError::getError();
        }
        $dbBroker->close();
        return json_encode($response);
    }
}
?>
