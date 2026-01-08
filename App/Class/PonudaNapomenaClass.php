<?php
/**
 * Description of PonudaNapomena
 *
 * @author n.lekic
 */
class PonudaNapomenaClass {

    public function PonudaNapomenaLoad(PonudaNapomena $ponudaNapomena) {
        $query = "select 
                    PN.PonudaNapomenaID as ponudaNapomenaID,
                    PN.PonudaID as ponudaID,
                    PN.StatusPonudaID,
                    PN.KorisnikID as korisnikID,
                    PN.Napomena,
                    PN.VremePostavke as vremePostavke
                    from ponudanapomena as PN
                    where PN.PonudaNapomenaID = ".$ponudaNapomena->getPonudaNapomenaID();
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
       
    public function PonudaNapomenaInsert (PonudaNapomena $ponudaNapomena) {
        $dbBroker = new CoreDBBroker();
        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();
        //START Deo za update Ponude tj statusa
        $ponuda = new Ponuda();
        $ponudaID = $ponudaNapomena->getPonudaID();
        $statusPonudaID = $ponudaNapomena->getStatusPonudaID();

        $napomena = $ponudaNapomena->getNapomena();

        $ponuda->setPonudaID($ponudaID);
        $ponuda->setStatusPonudaID($statusPonudaID);
        $condition = " PonudaID = ".$ponuda->getPonudaID();
        
        $ponudaIstorija->setPonudaID($ponudaID);
        $ponudaIstorija->setStatusPonudaID($statusPonudaID);
        $ponudaIstorija->setNapomena($napomena);

        $ponudaIstorija->setKorisnikID($ponudaNapomena->getKorisnikID());
        
        //END
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($ponudaNapomena); 
        $resultUpdate = $dbBroker->update($ponuda, $condition);
        $result2 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $resultUpdate && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana ponuda napomena");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaNapomenaUpdate(PonudaNapomena $ponudaNapomena) {
        $dbBroker = new CoreDBBroker();
        $ponudaIstorija = new PonudaIstorija();
        $ponudaIstorijaClass = new PonudaIstorijaClass();
        
        $ponudaIstorija->setPonudaID($ponudaNapomena->getPonudaID());
        $ponudaIstorija->setStatusPonudaID($ponudaNapomena->getStatusPonudaID());
        $ponudaIstorija->setKorisnikID($ponudaNapomena->getKorisnikID());
        
        $dbBroker->beginTransaction();
        $condition = " PonudaNapomenaID = ".$ponudaNapomena->getPonudaNapomenaID();
        $result = $dbBroker->update($ponudaNapomena, $condition);
        $result2 = $ponudaIstorijaClass->PonudaIstorijaInsert($ponudaIstorija, $dbBroker);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena ponuda napomena");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaNapomenaDelete(PonudaNapomena $ponudaNapomena) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " PonudaNapomenaID = ".$ponudaNapomena->getPonudaNapomenaID();
        $result = $dbBroker->delete($ponudaNapomena, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno obrisana napomena");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaNapomeneGetList(FilterPonudaNapomena $filter) {
        $query = "select
                    N.PonudaNapomenaID,
                    N.Napomena as Napomena,
                    concat_ws(' ', K.Ime, K.Prezime) as Uneo,
                    DATE_FORMAT(N.VremePostavke,'%d.%m.%Y') as Datum,
                    S.Naziv as StatusPonuda
                    from ponudanapomena N
                    inner join korisnik K on N.KorisnikID = K.KorisnikID
                    inner join statusponuda S on N.StatusPonudaID = S.StatusPonudaID
                    where ($filter->ponudaID is null or (N.PonudaID = $filter->ponudaID)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by N.VremePostavke desc ";
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
