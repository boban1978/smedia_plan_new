<?php
/**
 * Description of TmpSablonClass
 *
 * @author n.lekic
 */
class TmpSablonClass {
    private $naziv;
    private $pocetak;
    private $kraj;
    private $budzet;
    private $ucestalost;
    private $trajanjeSpota;

    
    private $zauzetiBlokoviZaPrikaz;
    private $izabraniBlokoviZaPrikaz;
    
    private $trenutnaCena;
    private $sortiraniDostupniZeljeniDani;
    private $ukupnoZauzetoBlokova;
    private $pomocniNiz;
    
    private $popust = 0;
    
    private $izabraniBlokovi = array();
    private $positions = array(1,2,3);

    private $sortiraniBlokoviSaCenama;
    
    private $control = 0;
    private $finish = 0;
    
    public function __construct($naziv, $pocetak, $kraj, $trajanjeSpota = 5) {
        // ulazni parametar zeljeni dani treba da bude niz sa vrednostima od 1 do 7 u zavisnosti koje dane zelimo 1 je ponedeljak a 7 je nedelja
        $this->naziv = $naziv;
        $this->pocetak = $pocetak;
        $this->kraj = $kraj;
        $this->trajanjeSpota = $trajanjeSpota;
        
        
        $sortiraniDostupniZeljeniDani = $this->GetAvailableDaysForCampaign($pocetak, $kraj, $trajanjeSpota);
        $this->sortiraniDostupniZeljeniDani = $sortiraniDostupniZeljeniDani;
        $this->sortiraniBlokoviSaCenama = $sortiraniDostupniZeljeniDani;
        
        //$this->pomocniNiz = $this->setPomocniNiz($sortiraniDostupniZeljeniDani);
        

        
        $this->trenutnaCena = 0;
        $this->ukupnoZauzetoBlokova = 0;
    }
    
    public function getIzabraniBlokovi() {
        return $this->izabraniBlokovi;
    }
    
    public function getTrenutnaCena() {
        return $this->trenutnaCena;
    }
    
    public function getZauzetiBlokoviZaPrikaz() {
        return $this->zauzetiBlokoviZaPrikaz;
    }
    
    public function getTrajanjeSpota() {
        return $this->trajanjeSpota;
    }
    
    public function getIzabraniBlokoviZaPrikaz() {
        return $this->izabraniBlokoviZaPrikaz;
    }
    
    public function getNaziv() {
        return $this->naziv;
    }
    
     
    public function getBudzet() {
        return $this->budzet;
    }
    
    
    public function getSortiraniDostupniZeljeniDani() {
        return $this->sortiraniDostupniZeljeniDani;
    }
    
    public function getSortiraniBlokoviSaCenama() {
        return $this->sortiraniBlokoviSaCenama;
    }
    
    public function setIzabraniBlokoviZaPrikaz($izabraniBlokoviZaPrikaz) {
        $this->izabraniBlokoviZaPrikaz = $izabraniBlokoviZaPrikaz;
    }
    
    public function setIzabraniBlokovi($izabraniBlokovi) {
        $this->izabraniBlokovi = $izabraniBlokovi;
    }
    
    public function setTrenutnaCena($trenutnaCena) {
        $this->trenutnaCena = $trenutnaCena;
    }
    
    public function getTmpSablon() {
//        foreach ($this->sortiraniDostupniZeljeniDani as $day => $blocks) {
//            foreach ($blocks as $key => $value) {
//                    $this->izabraniBlokovi[$day][$key] = $value;              
//                    $this->izabraniBlokoviZaPrikaz = $this->vratiIzabraneBlokoveZaPrikaz();
//            }
//        }
                return $this;
    }
    
      public function GetDaysForCampaign($startDate, $endDate) {
        $now = date('Y-m-d', time());
        if(strtotime($startDate) < strtotime($now)){
            $startDate = $now;
        }
        
        if(strtotime($endDate) < strtotime($now)){
             $daysForCampaign = array();
             return $daysForCampaign;
        }
        
        $daysForCampaign = array($startDate);
        $start = $startDate;
        $i=1;
        if (strtotime($startDate) < strtotime($endDate))
        {
            while (strtotime($start) < strtotime($endDate))
            {
                $start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                $daysForCampaign[] = $start;
                $i++;
            }
        }        
        return $daysForCampaign;

    }
    
    public function GetFreeBlocksForDays($daysForCampaign, $spotDuration) {
	$freeBlocksForDays = array();
	foreach($daysForCampaign as $availableDay) {
            
                 if($spotDuration > 30) {
                     $query = "select BlokID
                           from blok as B
                           where BlokID not in ( 
                                select blok.BlokID 
                                from blokzauzece 
                                join blok
                                on blokzauzece.BlokID = blok.BlokID
                                where (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi < $spotDuration) or (blokzauzece.Datum = '$availableDay' and blok.Vrsta = 1)
                            ) ";
                 } else {
            
                    $query = "select BlokID
                           from blok as B
                           where BlokID not in ( 
                                select blok.BlokID 
                                from blokzauzece 
                                join blok
                                on blokzauzece.BlokID = blok.BlokID
                                where (blokzauzece.Datum = '$availableDay' and blokzauzece.PreostaloSekundi < $spotDuration) or (blokzauzece.Datum = '$availableDay' and blok.Vrsta = 1 and blokzauzece.PreostaloSekundi < 30)
                            ) ";
                 }
  
                $dbBroker = new CoreDBBroker();
                $rows = $dbBroker->selectManyRows($query);
  		$blocks = array();
		foreach($rows['rows'] as $row) {
			$blocks[] = $row['BlokID'];
		}
                
                if(!empty($blocks)) {
                    $freeBlocksForDays[$availableDay] = $blocks;
                }
	}
        
        $this->zauzetiBlokoviZaPrikaz = $this->vratiZauzeteBlokove($freeBlocksForDays);
	return $freeBlocksForDays;
    }
    
    public function GetOrderForBlocks($freeBlocksForDays) {
	$priceForBlocks = array();
	
	foreach($freeBlocksForDays as $key=>$value) {
		$blocks = array();
		foreach($value as $block) {
                  
                        $testDay = date( "N", strtotime($key));
                        if(in_array($testDay, array(6,7))) {
                            $vikend = 1;
                        } else {
                            $vikend = 0;
                        }
                    
//                        $query1 = "select C.Cena
//                                    from cenovnik C
//                                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID 
//                                    where $spotDuration between KC.TrajanjeOd 
//                                    and KC.TrajanjeDo AND C.BlokID = $block
//                                    and C.Vikend = $vikend";
//                        
                        $dbBroker = new CoreDBBroker();
//                        $result1 = $dbBroker->selectOneRow($query1);
//                        $price = number_format($result1['Cena'], 2);
                        
                        $query2 = "select BZ.ZauzetaPrva, BZ.ZauzetaDruga
                                    from blokzauzece BZ
                                    where BZ.BlokID = $block AND BZ.Datum = '".$key."'";
                        $result2 = $dbBroker->selectOneRow($query2);
                        
                        
                        if($block%2) {
                            if($result2['ZauzetaPrva'] == 0) {
                                $blocks[$block.'/1'] = 1;
                            }
                            if($result2['ZauzetaDruga'] == 0) {
                                $blocks[$block.'/2'] = 1;
                            }

                            $blocks[$block.'/3'] = 1;
                        } else {
                            $blocks[$block.'/1'] = 1;
                        }
                        
                        
		}
		$priceForBlocks[$key] = $blocks;
	}
        
        return $priceForBlocks;
    }
    
    public function SortBlocksByPrice($priceForBlocks) {
            foreach($priceForBlocks as $key=>$value) {
                    uasort($value, function($a, $b){
                            if ($a == $b) {
                                    return 0;
                            }
                            return ($a < $b) ? -1 : 1;
                    });
                    $sortBlocksByPrice[$key] = $value;
            }
            return $sortBlocksByPrice;
    }
    
    public function GetAvailableDaysForCampaign($startDate, $endDate, $spotDuration){
        $messages = array();
        $daysForCampaign = $this->GetDaysForCampaign($startDate, $endDate);
        
        if(!empty($daysForCampaign)) {
            $daysWithFreeBlocks = $this->GetFreeBlocksForDays($daysForCampaign, $spotDuration);
            if(!empty($daysWithFreeBlocks)) {
                //$discount = $this->popust;
                $priceForBlocks = $this->GetOrderForBlocks($daysWithFreeBlocks);
                //$sortBlocksByPrice = $this->SortBlocksByPrice($priceForBlocks);
                return $priceForBlocks;
            } else {
                $messages['message'] = 'U izabranom periodu nema slobodnih termina za Vaš šabon.';
                return $messages;
            }
        } else {
            $messages['message'] = 'Niste izabrali ni jedan datum za Vasu kampanju.';
            return $messages;
        }
    }
    
    
    public function vratiZauzeteBlokove($freeBlocksForDays) {
        $zauzetiBlokoviZaPrikaz = array();
        foreach ($freeBlocksForDays as $key=>$value){
            $zauzetiBlokoviZaPrikaz[$key] = array();
            for ($i=1; $i<=72; $i++) {
                if (!in_array($i, $value)) {
                    $zauzetiBlokoviZaPrikaz[$key][] = $i;
                }
            }
        }
        return $zauzetiBlokoviZaPrikaz;
    }
    
    public function vratiIzabraneBlokoveZaPrikaz() {
        $izabraniBlokoviZaPrikaz = array();
        foreach ($this->izabraniBlokovi as $key=>$value) {
            $izabraniBlokoviZaPrikaz[$key] =array();
            foreach ($value as $blok=>$cena) {
                $niz = explode('/', $blok);
                $blokID = $niz[0];
                $izabraniBlokoviZaPrikaz[$key][] = $blokID;
            }
        }
        
        return $izabraniBlokoviZaPrikaz;
    }
    
public function izabraniPodaciZaPrikaz() {
        $dbBroker = new CoreDBBroker();
//        $izabraniBlokovi = $this->izabraniBlokovi;
        $data = array();
//        $izabraniBl = '';
//        foreach ($this->izabraniBlokoviZaPrikaz as $key=>$value) {
//            $izabraniBl = implode(',', $value);
//            $query = "SELECT BlokID, 
//                            concat_ws(' ', '2012-01-01', VremeStart) as VremeStart,
//                            concat_ws(' ', '2012-01-01', VremeEnd) as VremeEnd,
//                            Trajanje 
//            FROM blok WHERE BlokID IN ($izabraniBl)";
//            if ($izabraniBl <> ''){
//                $rows = $dbBroker->selectManyRows($query);
//                foreach($rows['rows'] as $row) {
//                    
//                    $blokoviPozicije = array($row['BlokID'].'/1', $row['BlokID'].'/2', $row['BlokID'].'/3');
//                    foreach ($blokoviPozicije as $blPoz) {
//                        if (in_array($blPoz, array_keys($izabraniBlokovi[$key]))) {
//                            $pozicija = substr($blPoz, -1, 1);
//                            break;;
//                        }
//                    }
//                        
//                    $data[$key][] = array('BlokID'=>$row['BlokID'], 'VremeStart'=>$row['VremeStart'], 'VremeEnd'=>$row['VremeEnd'], 'Trajanje'=>$row['Trajanje'], 'Flag'=>false, 'CommercialBlockOrderID'=>$pozicija);
//                }
//            }
//        }
//        
        $zauzetiBl = '';
        foreach ($this->zauzetiBlokoviZaPrikaz as $key=>$value) {
            $zauzetiBl = implode(',', $value);
            $query = "SELECT BlokID, 
                            concat_ws(' ', '2012-01-01', VremeStart) as VremeStart,
                            concat_ws(' ', '2012-01-01', VremeEnd) as VremeEnd,
                            Trajanje 
            FROM blok WHERE BlokID IN ($zauzetiBl)";
            if ($zauzetiBl <> '') {
                $rows = $dbBroker->selectManyRows($query);
                foreach($rows['rows'] as $row) {
                        $data[$key][] = array('BlokID'=>$row['BlokID'], 'VremeStart'=>$row['VremeStart'], 'VremeEnd'=>$row['VremeEnd'], 'Trajanje'=>$row['Trajanje'], 'Flag'=>true, 'CommercialBlockOrderID'=>'');
                }
            }
        }
        
        return $data;
    }
    
    public function GetResponse() {
        $data = $this->izabraniPodaciZaPrikaz();
        $i = 1;
        $j = 1;
        $konacno = array();
        $response = '{success:true, data:{"capmaignePrice" :'.$this->trenutnaCena.', ';
        $schedulerDates = '"schedulerDates" : [';      
        $schedulerCommercial = '"schedulerCommercial" : [';
        foreach ($data as $key=>$value) {
            $konacno['schedulerDates'][$i] = array('Id'=>$i, 'Name'=>$key); 
            $schedulerDates .= json_encode($konacno['schedulerDates'][$i]);
            $schedulerDates .= ",";
            foreach ($value as $row) {
                if($row['Flag']) {
                    $konacno['schedulerCommercial'][$j] = array('Id'=>$j, 'BlokId'=>$row['BlokID'], 'DatumBloka'=>$key, 'Title'=>'Blok zauzet drugim reklamama', 'StartDate'=>$row['VremeStart'], 'EndDate'=>$row['VremeEnd'], 'ResourceId'=>$i, 'Duration'=>$row['Trajanje'], 'OtherClient'=>$row['Flag'], 'CommercialBlockOrderID'=>$row['CommercialBlockOrderID']);
                } else {
                    $konacno['schedulerCommercial'][$j] = array('Id'=>$j, 'BlokId'=>$row['BlokID'], 'DatumBloka'=>$key, 'Title'=>$this->naziv, 'StartDate'=>$row['VremeStart'], 'EndDate'=>$row['VremeEnd'], 'ResourceId'=>$i, 'Duration'=>$this->trajanjeSpota, 'OtherClient'=>$row['Flag'], 'CommercialBlockOrderID'=>$row['CommercialBlockOrderID']);
                }
                $schedulerCommercial .= json_encode($konacno['schedulerCommercial'][$j]);
                $schedulerCommercial .= ",";
                $j++;
            }
            
            $i++;
        }
        $schedulerDates = count($data)>0 ? substr($schedulerDates, 0, strlen($schedulerDates)-1): $schedulerDates;
        $schedulerDates .= ']';
        $schedulerCommercial = count($data)>0 ? substr($schedulerCommercial, 0, strlen($schedulerCommercial)-1): $schedulerCommercial;
        $schedulerCommercial .= ']';
        $response .= $schedulerDates.', '.$schedulerCommercial.'}}';
        return $response;
    }
}

?>
