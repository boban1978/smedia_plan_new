<?php

class SwitchClass {
	
    public function GetSwitchRadio($radioStanicaID) {
		
/*
        switch ($radioStanicaID){
            case 1:
                $radio="s-juzni";
                break;
            case 2:
                $radio="s-mix";
                break;
            case 3:
                $radio="s-cafe";
                break;
            default:
                die('radio not set!!!');
        }
		
*/		
        $query = "SELECT radiostanica.Naziv FROM radiostanica WHERE radiostanica.RadioStanicaID = ".$radioStanicaID;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
		$radio = $result["Naziv"];
//		$dbBroker->close();
		
        return $radio;
    }


/*
		$switchclass = new SwitchClass();
		$radio = $switchclass->GetSwitchRadio($radioStanicaID);
		unset($switchclass);
*/


    public function GetSwitchID($radio){	
	
/*
        switch ($radio){
            case "s-juzni":
                $radios_id = 1;
                break;
            case "s-mix":
				$radios_id = 2;
                break;
            case "s-cafe":
				$radios_id = 3;
                break;
            default:
                die('radio not set!!!');
        }	
*/		
        $query = "SELECT radiostanica.RadioStanicaID FROM radiostanica WHERE radiostanica.Naziv = '".$radio."'";;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
		$radios_id = $result["RadioStanicaID"];
//		$dbBroker->close();
		
		return $radios_id;
    }

/*
		$switchclass = new SwitchClass();
		$radios_id = $switchclass->GetSwitchID($radio);
		unset($switchclass);
*/

	public function GetArray() {
		
		$arr_radios = array();
        $query = "SELECT radiostanica.Naziv FROM radiostanica";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
		foreach ($result["rows"] as $value) {
			$arr_radios[] = $value["Naziv"];	
		}
//		$arr_radios = array("s-juzni", "s-mix", "s-cafe");
		$dbBroker->close();
		return $arr_radios;
	}
/*
		$switchclass = new SwitchClass();
		$arr_radios = $switchclass->GetArray();
		unset($switchclass);
*/

	
}

?>


