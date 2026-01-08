<?php


class KorisnikClass {






    public function KorisnikLoad_init(Korisnik $korisnik) {
        $query = "select
                    K.KorisnikID as korisnikID,
                    K.Username as username,
                    K.Ime as ime,
                    K.Prezime as prezime,
                    K.Email as email,
                    K.Adresa as adresa,
                    K.TelefonFix as fiksniTelefon,
                    K.TelefonMob as mobilniTelefon,
                    K.AgencijaID as agencijaID,
                    K.KlijentID as klijentID,
                    case
                        when K.FlagKuca = 1 then 1
                        when K.FlagKlijent = 1 then 2
                        when K.FlagAgencija = 1 then 3
                    end as tipKorisnik,
                    case
                        when K.Aktivan = 1 then 'true'
                        when K.Aktivan = 0 then 'false'
                    end as aktivan
                    from korisnik as K
                    where K.KorisnikID = ".$korisnik->getKorisnikID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);



        /*
        //Deo za dohavtanje podataka o listi rola
        $queryRolaList = "select RolaID from korisnikrola where KorisnikID = ".$korisnik->getKorisnikID();
        $resultRolaList = $dbBroker->selectManyRows($queryRolaList);
        $roleList = "[";
        for ($i = 0; $i < count($resultRolaList['rows']); $i++) {
            $roleList .= $resultRolaList['rows'][$i]['RolaID'];
            $roleList .= ",";
        }
        $roleList = count($resultRolaList['rows'])>0 ? substr($roleList, 0, strlen($roleList)-1): $roleList;
        $roleList .= "]";
        $result['RoleList'] = $roleList;

        return $result;
*/











        //Deo za dohavtanje podataka o listi rola
        $queryRolaList = "select RolaID from korisnikrola where KorisnikID = ".$korisnik->getKorisnikID();
        $resultRolaList = $dbBroker->selectManyRows($queryRolaList);
        //$roleList = "[";
        $roleList="";
        for ($i = 0; $i < count($resultRolaList['rows']); $i++) {
            $rolaID=0+$resultRolaList['rows'][$i]['RolaID'];
            $roleList .= $rolaID;
            $roleList .= ",";
        }
        $roleList = count($resultRolaList['rows'])>0 ? substr($roleList, 0, strlen($roleList)-1): $roleList;
        //$roleList .= "]";

        //Deo za dohavtanje podataka o listi rola
        $queryPermisijaList = "select DISTINCT PermisijaID from rolapermisija where RolaID IN ( ".$roleList.")";
        $resultPermisijaList = $dbBroker->selectManyRows($queryPermisijaList);

        $permisijeList="";
        for ($i = 0; $i < count($resultPermisijaList['rows']); $i++) {
            $permisijaID=0+$resultPermisijaList['rows'][$i]['PermisijaID'];
            $permisijeList .= $permisijaID;
            $permisijeList .= ",";
        }
        $permisijeList = count($resultPermisijaList['rows'])>0 ? substr($permisijeList, 0, strlen($permisijeList)-1): $permisijeList;

        $roleList="[".$roleList."]";
        $permisijeList="[".$permisijeList."]";

        $result['RoleList'] = $roleList;
        $result['PermisijeList'] = $permisijeList;

        return $result;











    }










    public function KorisnikLoad(Korisnik $korisnik) {
        $query = "select 
                    K.KorisnikID as korisnikID,
                    K.Username as username,
                    K.Ime as ime,
                    K.Prezime as prezime,
                    K.Email as email,
                    K.Adresa as adresa,
                    K.TelefonFix as fiksniTelefon,
                    K.TelefonMob as mobilniTelefon,
                    K.AgencijaID as agencijaID,
                    K.KlijentID as klijentID,
                    case 
                        when K.FlagKuca = 1 then 1
                        when K.FlagKlijent = 1 then 2
                        when K.FlagAgencija = 1 then 3  
                    end as tipKorisnik,
                    case 
                        when K.Aktivan = 1 then 'true'
                        when K.Aktivan = 0 then 'false'
                    end as aktivan
                    from korisnik as K
                    where K.KorisnikID = ".$korisnik->getKorisnikID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        
        //Deo za dohavtanje podataka o listi rola
        $queryRolaList = "select RolaID from korisnikrola where KorisnikID = ".$korisnik->getKorisnikID();
        $resultRolaList = $dbBroker->selectManyRows($queryRolaList);
        $roleList = "[";
        for ($i = 0; $i < count($resultRolaList['rows']); $i++) {
            $roleList .= $resultRolaList['rows'][$i]['RolaID'];
            $roleList .= ",";
        }
        $roleList = count($resultRolaList['rows'])>0 ? substr($roleList, 0, strlen($roleList)-1): $roleList;
        $roleList .= "]";
        $result['RoleList'] = $roleList;
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:'.json_encode($result));
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
        public function KorisnikDetailsLoad(Korisnik $korisnik) {
        $query = "select 
                    K.KorisnikID as korisnikID,
                    K.Ime as ime,
                    K.Prezime as prezime,
                    K.Email as email,
                    K.TelefonFix as fiksniTelefon,
                    K.TelefonMob as mobilniTelefon
                    from korisnik as K
                    where K.KorisnikID = ".$korisnik->getKorisnikID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);

        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:'.json_encode($result));
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KorisnikImePrezimeGet(Korisnik $korisnik) {
        $query = "select 
                    K.Ime as ime,
                    K.Prezime as prezime,
                    case
                        when K.FlagKuca = 1 then 1
                        when K.FlagKlijent = 1 then 2
                        when K.FlagAgencija = 1 then 3
                    end as tipKorisnik
                    from korisnik as K
                    where K.KorisnikID = ".$korisnik->getKorisnikID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);


        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:'.json_encode($result));
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    //
    public function KomercijalistaGetList() {


       $query = "select
                K.KorisnikID,
                CONCAT_WS(' ', K.Ime, K.Prezime) as ImePrezime
                from korisnik as K
                where K.FlagKuca = 1 and K.Aktivan = 1";

        global $korisnik_init;
        if($korisnik_init['tipKorisnik']==3) {
            $query = "select
                K.KorisnikID,
                CONCAT_WS(' ', K.Ime, K.Prezime) as ImePrezime
                from korisnik as K
                where K.Aktivan = 1 AND K.KorisnikID=" . $korisnik_init['korisnikID'];
        }


        $querySort = " order by K.Ime asc, K.Prezime asc ";
        $query .= $querySort;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:'.json_encode($result['rows']).', total:'.$result['numRows']);
            }
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KorisnikInsert (Korisnik $korisnik) {
        $dbBroker = new CoreDBBroker();
        /** @todo Odraditi obuhavtanje ovog dela inserta korisniak i njegovih rola transakcijom
         *  Posle inserta korisnika treba dohvatiti ID zadnjeg insertovanog korrisnika za koji ce s evezati ID rola iz role list
         */
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($korisnik);
        $userID = $dbBroker->getLastInsertedId();
        //$userID = "Rezultat ovog IDa zadnjeg insertovanog";
        $roleList = $korisnik->getRoleList();
        $result1 = true;
        for ($i = 0; $i < count($roleList); $i++) {
            $korisnikRola = new KorisnikRola();
            $korisnikRola->setKorisnikID($userID);
            $korisnikRola->setRolaID($roleList[$i]);
            $korisnikRola->setAktivan('true');
            $result1 = $dbBroker->insert($korisnikRola);
            $result1 &=$result1;
        }
                
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan korisnik");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KorisnikUpdate(Korisnik $korisnik) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        //Update START
        $condition = " KorisnikID = ".$korisnik->getKorisnikID();
        $result = $dbBroker->update($korisnik, $condition);
        //Update END
        //Delete KorinsikRola START
        $korisnikRola = new KorisnikRola();
        $result2 = $dbBroker->delete($korisnikRola, $condition);
        //Delete KorinsikRola END







        $result1 = true;
        switch (1) {
            case $korisnik->getFlagKuca()=='true':

                $roleList = $korisnik->getRoleList();
                for ($i = 0; $i < count($roleList); $i++) {
                    $korisnikRola->setKorisnikID($korisnik->getKorisnikID());
                    $korisnikRola->setRolaID($roleList[$i]);
                    $korisnikRola->setAktivan('true');
                    $result11 = $dbBroker->insert($korisnikRola);
                    $result1 &=$result11;
                }

                break;
            case $korisnik->getFlagAgencija()=='true':

                $query = "SELECT * from rola r WHERE r.Naziv ='Radnik agencije'";
                $dbBroker = new CoreDBBroker();
                $result_rola = $dbBroker->selectOneRow($query);

                if($result_rola){
                    $rolaID=0+$result_rola['RolaID'];
                    $korisnikRola->setKorisnikID($korisnik->getKorisnikID());
                    $korisnikRola->setRolaID($rolaID);
                    $korisnikRola->setAktivan('true');
                    $result11 = $dbBroker->insert($korisnikRola);
                    $result1 &=$result11;
                }

                break;
            case $korisnik->getFlagKlijent()=='true':

                $query = "SELECT * from rola r WHERE r.Naziv ='Direktan klijent'";
                $dbBroker = new CoreDBBroker();
                $result_rola = $dbBroker->selectOneRow($query);

                if($result_rola){
                    $rolaID=0+$result_rola['RolaID'];
                    $korisnikRola->setKorisnikID($korisnik->getKorisnikID());
                    $korisnikRola->setRolaID($rolaID);
                    $korisnikRola->setAktivan('true');
                    $result11 = $dbBroker->insert($korisnikRola);
                    $result1 &=$result11;
                }

                break;
        }






                
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1 && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno promenjeni podaci za korisnika");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    //Funkcija za brisanje 
    public function KorisnikDelete(Korisnik $korisnik) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KorisnikID = ".$korisnik->getKorisnikID();
        //Brisanje rola za korisnika START
        $korisnikRola = new KorisnikRola();
        $resultDeleteRola = $dbBroker->delete($korisnikRola, $condition);
        //Brisanje rola za korisnika END
        $result = $dbBroker->delete($korisnik, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $resultDeleteRola) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno obrisana stavka");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KorisnikGetList(FilterKorisnik $filter) {
        $query = "select 
                    K.KorisnikID,
                    K.Username,
                    CONCAT_WS(' ', K.Ime, K.Prezime) as ImePrezime,
                    case 
                        when FlagKlijent = 1 then 'Direktan klijent'
                        when FlagAgencija = 1 then 'Radnik agencije'
                        when FlagKuca = 1 then 'Interni korisnik'
                    end as TipKorisnik,
                    case 
                        when Aktivan = 1 then 'Aktivan'
                        when Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from korisnik as K
        where ('$filter->korisnickoImeFilter' = '' or (K.Username like '%$filter->korisnickoImeFilter%'))
        and ('$filter->aktivanFilter' = '' or (K.Aktivan = '$filter->aktivanFilter'))
        and ('$filter->imePrezimeFilter' = '' or CONCAT_WS(' ', K.Ime, K.Prezime) = '%$filter->imePrezimeFilter%') ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.Username asc ";
        }
        $query .= $querySort;
        //$query .= "LIMIT $filter->start, $filter->limit";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:'.json_encode($result['rows']).', total:'.$result['numRows']);
            }
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    /**
     * Funkcija koja proverava podatke o ulogovanom korsiniku
     * @author Nikola Lekic
     * @access Public
     * @param $username Username korisnika
     * @param $password Password korisnika
     */	
	 /*
	public static function KorisnikCheckUserPassword ($username, $password) {
            $query = "SELECT CheckUserPassword ('$username', '$password') as RESULT";
            $dbBroker = new CoreDBBroker();
            $result = $dbBroker->selectOneRow($query);
//            $responseNew = new CoreAjaxResponseInfo();
//            if ($result) {
//                $responseNew->SetSuccess('true');
//                $responseNew->SetData('data:'.json_encode($result));
//            }
//            else {
//                $responseNew->SetSuccess('false');
//                //Ovde treba da setujemo gresku koja s edesila na bazi
//                $responseNew->SetMessage('Greška: Neka greska');
//            }
            $dbBroker->close();
            return $result['RESULT'];
        }
        */
        
		public static function KorisnikCheckUserPassword ($username, $password) {
			$dbBroker = new CoreDBBroker();
        
			$username = mysql_real_escape_string($username);
			$password = mysql_real_escape_string($password);
			
			$query = "SELECT K.KorisnikID, K.Username FROM korisnik K
                        WHERE K.Username = '$username' AND K.Password = '$password'";
            
                        $result = $dbBroker->selectOneRow($query);
                        $dbBroker->close();
			if(isset($result['KorisnikID']) && $result['KorisnikID'] != ''){
				return 'OK';
			} else {
				return 'Pogresni parametri za logovanje.';
			}
        }
        
        public static function KorisnikFetchUserLoginDetails ($username) {
            /*
            $query = "SELECT  K.KorisnikID,
                        K.Username,
                        case 
                            when K.Aktivan = 1 then 'true'
                            when K.Aktivan = 0 then 'false'
                        end as Aktivan,
                        case 
                            when FlagKuca = 1 then ".TipKorisnika::InterniKorisnik."
                            when FlagKlijent = 1 then ".TipKorisnika::KlijentskiKorisnik."
                            when FlagAgencija = 1 then ".TipKorisnika::AgencijskiKorisnik."                          
                        end as TipKorisnik
                        FROM korisnik K
                        WHERE K.Username = '$username'";
            */
            $dbBroker = new CoreDBBroker();
			
            $username = mysql_real_escape_string($username);
			
            $query = "SELECT  K.KorisnikID,
                        K.Username,
                        case 
                            when K.Aktivan = 1 then 'true'
                            when K.Aktivan = 0 then 'false'
                        end as Aktivan,
                        case 
                            when FlagKuca = 1 then 1
                            when FlagKlijent = 1 then 2
                            when FlagAgencija = 1 then 3                     
                        end as TipKorisnik,
                        K.KlijentID,
                        K.AgencijaID
                        FROM korisnik K
                        WHERE K.Username = '$username'";
            
            
            $result = $dbBroker->selectOneRow($query);
            $dbBroker->close();
            return $result;
	}
        /**
         * Funkcija koja vrsi evidenciju o ulogovanom korisniku
         * @author Nikola Lekic
         * @access public
         * @param $id_kor
         */
	public static function RegisterLogin($korisnikID){
		$dbBroker = new CoreDBBroker();
                $log = new Log();
                $log->setKorisnikID($korisnikID);
                $result = $dbBroker->insert($log);
                $dbBroker->close();
                return $result;
	}
}
?>
