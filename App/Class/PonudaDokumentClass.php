<?php
class PonudaDokumentClass {
    public function PonudaDokumentLoad(PonudaDokument $ponudaDokument) {
        $query = "select 
                    PD.PonudaDokumentID as ponudaDokumentID,
                    PD.PonudaID as ponudaID,
                    PD.Naziv as naziv,
                    PD.Link as link,
                    PD.KorisnikID as korisnikID,
                    PD.VremePostavke as vremePostavke
                    from ponudadokument as PD
                    where PD.ponudaDokumentID = ".$ponudaDokument->getPonudaDokumentID();
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
    
    public function PonudaDokumentGetForComboBox(PonudaDokument $ponudaDokument) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($ponudaDokument);
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
    
    public function PonudaDokumentInsert (PonudaDokument $ponudaDokument) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($ponudaDokument);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana ponuda dokument");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaDokumentUpdate(PonudaDokument $ponudaDokument) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " PonudaDokumentID = ".$ponudaDokument->getPonudaDokumentID();
        $result = $dbBroker->update($ponudaDokument, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena ponuda dokument");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function PonudaDokumentDelete(PonudaDokument $ponudaDokument) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " PonudaDokumentID = ".$ponudaDokument->getPonudaDokumentID();
        $result = $dbBroker->delete($ponudaDokument, $condition);
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
    
    public function PonudaDokumentGetList(FilterPonudaDokument $filter) {
        $query = "select
                    D.PonudaDokumentID,
                    D.Naziv,
                    REPLACE(D.Link,'../../','../') as Link,
                    concat_ws(' ', K.Ime, K.Prezime) as Uneo,
                    DATE_FORMAT(D.VremePostavke,'%d.%m.%Y') as Datum
                    from ponudadokument D
                    inner join korisnik K on D.KorisnikID = K.KorisnikID
                    where ($filter->ponudaID is null or (D.PonudaID = $filter->ponudaID)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by D.VremePostavke desc ";
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
