<?php
class PonudaClass {
    public function PonudaLoad(Ponuda $ponuda) {
        $query = "select 
                    P.PonudaID as ponudaID,
                    P.KlijentID as klijentID,
                    P.Korisnik as korisnik,
                    P.Sadrzaj as sadrzaj,
                    P.Vrednost as vrednost,
                    P.StatusPonudaID as statusPonudaID,
                    P.VremePostavke as vremePostavke
                    from ponuda as P
                    where P.ponudaID = ".$ponuda->getPonudaID();
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
    
    public function PonudaGetForComboBox(Ponuda $ponuda) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($ponuda);
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
    
    public function PonudaInsert (Ponuda $ponuda, $dbBroker) {
        //$dbBroker = new CoreDBBroker();

        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();

        //$dbBroker->beginTransaction();
        $result = $dbBroker->insert($ponuda); 
        
        $ponudaLastId = $dbBroker->getLastInsertedId();
        
        $ponudaIstorija->setPonudaID($ponudaLastId);
        $ponudaIstorija->setStatusPonudaID($ponuda->getStatusPonudaID());
        $ponudaIstorija->setNapomena($ponuda->getSadrzaj());

        $ponudaIstorija->setKorisnikID($ponuda->getKorisnikID());
        
        $result2 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);


        return ($result && $result2);

        /*
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana ponuda");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;*/
    }
    
    public function PonudaUpdate(Ponuda $ponuda) {
        $dbBroker = new CoreDBBroker();
        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();
        $dbBroker->beginTransaction();
        $condition = " PonudaID = ".$ponuda->getPonudaID();
        $result = $dbBroker->update($ponuda, $condition);
        
        $ponudaIstorija->setPonudaID($ponuda->getPonudaID());
        $ponudaIstorija->setStatusPonudaID($ponuda->getStatusPonudaID());
        $ponudaIstorija->setKorisnikID($ponuda->getKorisnikID());
        
        $result2 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);
        
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena ponuda");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaDelete(Ponuda $ponuda) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " PonudaID = ".$ponuda->getPonudaID();
        $result = $dbBroker->delete($ponuda, $condition);
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
    
    public function PonudaGetList(FilterPonuda $filter) {
        $query = "select 
                    P.PonudaID,
                    P.Sadrzaj,
                    /*P.Vrednost,*/
                    C.CenaUkupno as Vrednost,
                    C.Naziv as Kampanja,
                    S.Naziv as Status,
                    CONCAT_WS(' ', K.Ime, K.Prezime) as Uneo,
                    DATE_FORMAT(P.VremePostavke,'%d.%m.%Y') as Datum
                    from ponuda as P
                    inner join korisnik K on P.KorisnikID = K.KorisnikID
                    inner join statusponuda S on P.StatusPonudaID = S.StatusPonudaID
                    inner join kampanja C on P.KampanjaID = C.KampanjaID
                where ($filter->klijentID is null or (P.KlijentID = $filter->klijentID)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by P.VremePostavke desc ";
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
