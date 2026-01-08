<?php
class KontaktClass {
    public function KontaktLoad(Kontakt $kontakt) {
        $query = "select 
                    K.KontaktID as kontaktID,
                    K.KlijentID as klijentID,
                    K.Ime as ime,
                    K.Prezime as prezime,
                    K.Adresa as adresa,
                    K.Funkcija as funkcija,
                    K.Telefon1 as telefon1,
                    K.Telefon2 as telefon2,
                    K.Telefon3 as telefon3,
                    K.Email as email
                    from kontakt as K
                    where K.kontaktID = ".$kontakt->getKontaktID();
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
    
    public function KontaktGetForComboBox(Kontakt $kontakt) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($kontakt);
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
    
    public function KontaktInsert (Kontakt $kontakt) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kontakt);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan kontakt");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KontaktUpdate(Kontakt $kontakt) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KontaktID = ".$kontakt->getKontaktID();
        $result = $dbBroker->update($kontakt, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen kontakt");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KontaktDelete(Kontakt $kontakt) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KontaktID = ".$kontakt->getKontaktID();
        $result = $dbBroker->delete($kontakt, $condition);
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
    
    public function KontaktGetList(FilterKontakt $filter) {
        $query = "select 
                    K.KontaktID,
                    CONCAT_WS(' ', K.Ime, K.Prezime) as ImePrezime,
                    K.Adresa,
                    K.Email,
                    K.Telefon1 as Telefon
                    from kontakt as K
        where ($filter->klijentID is null or (K.KlijentID = $filter->klijentID)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.Ime, K.Prezime asc ";
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
