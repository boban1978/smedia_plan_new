<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KlijentClass
 *
 * @author n.lekic
 */
class KlijentClass {

    public function KlijentLoad(Klijent $klijent) {

        $query = "select 
                    K.KlijentID as klijentID,
                    K.Naziv as naziv,
                    K.Adresa as adresa,
                    K.MaticniBroj as maticni,
                    K.Pib as pib,
                    K.Racun as racun,
                    K.TelefonFix as telefonFiksni,
                    K.TelefonMob as telefonMobilni,
                    K.Email as email,
                    K.Drzava as drzava,
                    K.AdresaRacun as adresaZaRacun,
                    K.TipUgovoraID as tipUgovoraID,
                    K.TeritorijaPokrivanja as teritorijaPokrivanja,
                    K.DelatnostID as delatnostID,
                    K.Popust as popust,
                    case 
                        when K.Aktivan = 1 then 'true'
                        when K.Aktivan = 0 then 'false'
                    end as aktivan
                    from klijent as K
                    where K.KlijentID = " . $klijent->getKlijentID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);

        //Deo za dohavtanje podataka o listi rola
        $queryAgencijaList = "select AgencijaID from agencijaklijent where KlijentID = " . $klijent->getKlijentID();
        $resultAgencijaList = $dbBroker->selectManyRows($queryAgencijaList);
        $agencijaList = "[";
        for ($i = 0; $i < count($resultAgencijaList['rows']); $i++) {
            $agencijaList .= $resultAgencijaList['rows'][$i]['AgencijaID'];
            $agencijaList .= ",";
        }
        $agencijaList = count($resultAgencijaList['rows']) > 0 ? substr($agencijaList, 0, strlen($agencijaList) - 1) : $agencijaList;
        $agencijaList .= "]";
        $result['agencijaList'] = $agencijaList;
        //Deo za dohavtanje podataka o listi delatnosti
        $queryDelatnostList = "select DelatnostID from klijentdelatnost where KlijentID = " . $klijent->getKlijentID();
        $resultDelatnostList = $dbBroker->selectManyRows($queryDelatnostList);
        $delatnostList = "[";
        for ($j = 0; $j < count($resultDelatnostList['rows']); $j++) {
            $delatnostList .= $resultDelatnostList['rows'][$j]['DelatnostID'];
            $delatnostList .= ",";
        }
        $delatnostList = count($resultDelatnostList['rows']) > 0 ? substr($delatnostList, 0, strlen($delatnostList) - 1) : $delatnostList;
        $delatnostList .= "]";
        $result['delatnostList'] = $delatnostList;

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

    public function KlijentGetForComboBox(Klijent $object, $filter) {


        $uslov="";
        global $korisnik_init;
        if($korisnik_init['tipKorisnik']==3){

                    if (!empty($filter)) {

                        $query = "select DISTINCT
K.KlijentID as EntryID,
K.Naziv as EntryName
FROM
klijent AS K
INNER JOIN agencijaklijent AK ON K.KlijentID = AK.KlijentID
WHERE
K.Naziv like '%$filter%' AND AK.AgencijaID = ".$korisnik_init['agencijaID'];

                    } else {
                        $query = "select DISTINCT
K.KlijentID as EntryID,
K.Naziv as EntryName
FROM
klijent AS K
INNER JOIN agencijaklijent AK ON K.KlijentID = AK.KlijentID
WHERE AK.AgencijaID = ".$korisnik_init['agencijaID'];
                    }

        }else{

                    if (!empty($filter)) {

                        $query = "select
                            K.KlijentID as EntryID,
                            K.Naziv as EntryName
                            from klijent as K
                            where K.Naziv like '%$filter%'";
                    } else {

                        $query = "select
                            K.KlijentID as EntryID,
                            K.Naziv as EntryName
                            from klijent as K";
                    }

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

    public function KlijentInsert(Klijent $klijent) {
        $dbBroker = new CoreDBBroker();
        /** @todo Odraditi obuhavtanje ovog dela inserta korisniak i njegovih rola transakcijom
         *  Posle inserta korisnika treba dohvatiti ID zadnjeg insertovanog korrisnika za koji ce s evezati ID rola iz role list
         */

        $pib=mysql_real_escape_string($klijent->getPib());

        $query = "SELECT * FROM klijent WHERE Pib = '" . $pib . "'";
        $result = $dbBroker->selectOneRow($query);
        if($result){
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Klijent sa ovim pibom već postoji u bazi podataka!!!");
            $dbBroker->close();
            return $responseNew;
        }





        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($klijent);
        $klijentID = $dbBroker->getLastInsertedId();
        //$userID = "Rezultat ovog IDa zadnjeg insertovanog";
        $agencjaList = $klijent->getAgencijaList();
        $result1 = true;
        for ($i = 0; $i < count($agencjaList); $i++) {
            $agencijaKlijent = new AgencijaKlijent();
            $agencijaKlijent->setKlijentID($klijentID);
            $agencijaKlijent->setAgencijaID($agencjaList[$i]);
            $agencijaKlijent->setAktivan('true');
            $result1 = $dbBroker->insert($agencijaKlijent);
            $result1 &=$result1;
        }

        $delatnostList = $klijent->getDelatnostList();
        $result2 = true;
        for ($j = 0; $j < count($delatnostList); $j++) {
            $delatnostKlijent = new KlijentDelatnost();
            $delatnostKlijent->setKlijentID($klijentID);
            $delatnostKlijent->setDelatnostID($delatnostList[$j]);
            $result2 = $dbBroker->insert($delatnostKlijent);
            $result2 &=$result2;
        }

        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1 && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan klijent");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function KlijentUpdate(Klijent $klijent) {
        $dbBroker = new CoreDBBroker();


        $pib=mysql_real_escape_string($klijent->getPib());

        $query = "SELECT * FROM klijent WHERE Pib = '" . $pib . "' AND KlijentID<>".$klijent->getKlijentID();
        $result = $dbBroker->selectOneRow($query);
        if($result){
            $responseNew = new CoreAjaxResponseInfo();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Klijent sa ovim pibom već postoji u bazi podataka!!!");
            $dbBroker->close();
            return $responseNew;
        }



        $dbBroker->beginTransaction();
        $condition = " KlijentID = " . $klijent->getKlijentID();
        $result = $dbBroker->update($klijent, $condition);

        $agencijaKlijent = new AgencijaKlijent();
        $result1 = $dbBroker->delete($agencijaKlijent, $condition);
        $agencjaList = $klijent->getAgencijaList();
        $result2 = true;
        for ($i = 0; $i < count($agencjaList); $i++) {
            $agencijaKlijent = new AgencijaKlijent();
            $agencijaKlijent->setKlijentID($klijent->getKlijentID());
            $agencijaKlijent->setAgencijaID($agencjaList[$i]);
            $agencijaKlijent->setAktivan('true');
            $result2 = $dbBroker->insert($agencijaKlijent);
            $result2 &=$result2;
        }


        $klijentDelatnost = new KlijentDelatnost();
        $result3 = $dbBroker->delete($klijentDelatnost, $condition);
        $delatnostList = $klijent->getDelatnostList();
        $result4 = true;
        for ($j = 0; $j < count($delatnostList); $j++) {
            $klijentDelatnost = new KlijentDelatnost();
            $klijentDelatnost->setKlijentID($klijent->getKlijentID());
            $klijentDelatnost->setDelatnostID($delatnostList[$j]);
            $result4 = $dbBroker->insert($klijentDelatnost);
            $result4 &=$result4;
        }

        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1 && $result2 && $result3 && $result4) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen klijent");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija za brisanje 
    public function KlijentDelete(Klijent $klijent) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KlijentID = " . $klijent->getKlijentID();

        //Brisanje svih agencija vezanih za klijenta prvo
        $agencijaKlijent = new AgencijaKlijent();
        $result1 = $dbBroker->delete($agencijaKlijent, $condition);

        $result = $dbBroker->delete($klijent, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1) {
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

    //Funkcija za punjenje klijenta
    public function KlijentGetList(FilterKlijent $filter) {
        if ($filter->agencijaListFilter <> '') {
            $queryAgencijaListFilter = "and (AK.AgencijaID in (" . $filter->agencijaListFilter . "))";
        } else {
            $queryAgencijaListFilter = "";
        }
        $query = "select 
                    distinct
                    K.KlijentID,
                    K.Naziv,
                    K.Adresa,
                    K.Pib,
                    K.MaticniBroj,
                    D.Naziv as Delatnost,
                    case 
                        when K.Aktivan = 1 then 'Aktivan'
                        when K.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from klijent as K
                    left outer join delatnost D on K.DelatnostID = D.DelatnostID
                    left outer join agencijaklijent AK on K.KlijentID = AK.KlijentID
                    left outer join kontakt KO on K.KlijentID = KO.KlijentID
        where ('$filter->naziv' = '' or (K.Naziv like '%$filter->naziv%'))
        and ('$filter->adresa' = '' or (K.Adresa like '%$filter->adresa%'))
        and ('$filter->drzava' = '' or (K.Drzava like '%$filter->drzava%'))
        and ('$filter->pib' = '' or (K.Pib like '%$filter->pib%'))
        and ('$filter->maticniBroj' = '' or (K.MaticniBroj like '%$filter->maticniBroj%'))
        and ('$filter->kontaktIme' = '' or (KO.Ime like '%$filter->kontaktIme%'))
        and ('$filter->kontaktEmail' = '' or (KO.Email like '%$filter->kontaktEmail%'))
        and ($filter->tipUgovoraID is null or (K.TipUgovoraID = $filter->tipUgovoraID))
        and ($filter->delatnostID is null or (K.DelatnostID = $filter->delatnostID)) " . $queryAgencijaListFilter;
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.Naziv asc ";
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

    //Funkcija za punjenje klijenta za odredjenu agenciju
    public function KlijentAgencijaGetList($agencijaID, $start, $limit, $sort, $dir, $page) {
        $query = "select 
                    distinct
                    K.KlijentID,
                    K.Naziv,
                    K.Adresa,
                    D.Naziv as Delatnost,
                    case 
                        when K.Aktivan = 1 then 'Aktivan'
                        when K.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from klijent as K
                    left outer join delatnost D on K.DelatnostID = D.DelatnostID
                    left outer join agencijaklijent AK on K.KlijentID = AK.KlijentID
                    left outer join kontakt KO on K.KlijentID = KO.KlijentID
        where AK.AgencijaID  = $agencijaID ";
        if ($sort != '') {
            $querySort = " order by $sort $dir";
        } else {
            $querySort = " order by K.Naziv asc ";
        }
        $query .= $querySort;
        //file_put_contents("neki.txt", $query);
        //$query .= "LIMIT $filter->start, $filter->limit";
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

    //Funkcija koja dohavta sve agencije kojima pripada konkrenti klijent
    public function GetAllAgencijaZaKlijenta(Klijent $klijent) {
        $klijentId = $klijent->getKlijentID();
        $query = "select 
                    AK.AgencijaID as EntryID,
                    A.Naziv as EntryName
                    from agencijaklijent AK
                    inner join Agencija A on AK.AgencijaID = A.AgencijaID
        where AK.KlijentID  =  $klijentId";
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

}

?>
