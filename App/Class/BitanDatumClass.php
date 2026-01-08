<?php

class BitanDatumClass {
    
    public function BitanDatumLoad(BitanDatum $bitanDatum) {
        
        $query = "select 
                    B.BitanDatumID as bitanDatumID,
                    B.Datum as datum,
                    B.Opis as opis
                    case 
                        when B.Aktivan = 1 then 'true'
                        when B.Aktivan = 0 then 'false'
                    end as aktivan
                    from bitandatum as B
                    where B.BitanDatumID = ".$bitanDatum->getBitanDatumID();
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
    
 
    public function BitanDatumInsert (BitanDatum $bitanDatum) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($bitanDatum);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan bitan datum");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function BitanDatumUpdate(BitanDatum $bitanDatum) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BitanDatumID = ".$bitanDatum->getBitanDatumID();
        $result = $dbBroker->update($bitanDatum, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen bitan datum");
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
    public function BitanDatumDelete(BitanDatum $bitanDatum) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BitanDatumID = ".$bitanDatum->getBitanDatumID();
        $result = $dbBroker->delete($bitanDatum, $condition);
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
    
    //Funkcija za punjenje bitnih datuma
    public function BitanDatumGetList(FilterBitanDatum $filter) {
        $query = "select 
                    B.BitanDatumID,
                    DATE_FORMAT(B.Datum,'%d.%m.%Y') as Datum,
                    B.Opis,    
                    case 
                        when B.Aktivan = 1 then 'Aktivan'
                        when B.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from bitandatum as B
        where ($filter->ID is null or (B.ID = $filter->ID))
        and ($filter->vrsta is null or (B.Vrsta = $filter->vrsta)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by B.Datum asc ";
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

