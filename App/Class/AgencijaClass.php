<?php
/**
 * Description of AgencijaClass
 *
 * @author n.lekic
 */
class AgencijaClass {
    public function AgencijaGetForComboBox(Agencija $agencija) {
        $dbBroker = new CoreDBBroker();

        global $korisnik_init;
        if($korisnik_init['tipKorisnik']==3){
            $query = "select
                A.AgencijaID as EntryID,
                A.Naziv as EntryName
                from agencija as A
                where AgencijaID = ".$korisnik_init['agencijaID'];

            $dbBroker = new CoreDBBroker();
            $result = $dbBroker->selectManyRows($query);

        }else{
            $result = $dbBroker->getDataForComboBox($agencija);
        }




        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:'.json_encode($result['rows']));
        }
        else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;
    }
    
    public function AgencijaLoad(Agencija $agencija) {
        
        $query = "select 
                    A.AgencijaID as agencijaID,
                    A.Naziv as naziv,
                    A.Adresa as adresa,
                    A.MaticniBroj as maticni,
                    A.Pib as pib,
                    A.Racun as racun,
                    A.TelefonFix as telefonFiksni,
                    A.TelefonMob as telefonMobilni,
                    A.Email as email,
                    A.Drzava as drzava,
                    A.KontaktOsoba as kontaktOsoba,
                    A.AdresaZaRacun as adresaZaRacun,
                    A.Popust as popust,
                    case 
                        when A.Aktivan = 1 then 'true'
                        when A.Aktivan = 0 then 'false'
                    end as aktivan
                    from agencija as A
                    where A.AgencijaID = ".$agencija->getAgencijaID();
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
    
 
    public function AgencijaInsert (Agencija $agencija) {
        $dbBroker = new CoreDBBroker();
        /** @todo Odraditi obuhavtanje ovog dela inserta korisniak i njegovih rola transakcijom
         *  Posle inserta korisnika treba dohvatiti ID zadnjeg insertovanog korrisnika za koji ce s evezati ID rola iz role list
         */
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($agencija);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana agencija");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function AgencijaUpdate(Agencija $agencija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " AgencijaID = ".$agencija->getAgencijaID();
        $result = $dbBroker->update($agencija, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena agencija");
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
    public function AgencijaDelete(Agencija $agencija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " AgencijaID = ".$agencija->getAgencijaID();
        $result = $dbBroker->delete($agencija, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
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
    
    //Funkcija za punjenje klijenta
    public function AgencijaGetList(FilterAgencija $filter) {
        $query = "select
                    distinct 
                    A.AgencijaID,
                    A.Naziv,
                    A.Adresa,
                    A.KontaktOsoba,
                    case 
                        when A.Aktivan = 1 then 'Aktivan'
                        when A.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from agencija as A
                    left outer join agencijaklijent as AK on A.AgencijaID = AK.AgencijaID 
        where ('$filter->naziv' = '' or (A.Naziv like '%$filter->naziv%'))
        and ('$filter->adresa' = '' or (A.Adresa like '%$filter->adresa%')) 
        and ('$filter->drzava' = '' or (A.Drzava like '%$filter->drzava%'))
        and ('$filter->kontakt' = '' or (A.KontaktOsoba like '%$filter->kontakt%'))
        and ('$filter->email' = '' or (A.Email like '%$filter->email%'))
        and ($filter->klijentID is null or (AK.KlijentID = $filter->klijentID))

        and ($filter->agencijaID is null or (A.AgencijaID = $filter->agencijaID))

        ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by A.Naziv asc ";
        }
        $query .= $querySort;
        //$query .= "LIMIT $filter->start, $filter->limit";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result===0) {
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
}

?>
