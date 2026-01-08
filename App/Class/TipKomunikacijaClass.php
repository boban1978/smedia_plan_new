<?php

class TipKomunikacijaClass {
    public function TipKomunikacijaGetForComboBox(TipKomunikacija $tipKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($tipKomunikacija);
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
    
    public function TipKomunikacijaLoad(TipKomunikacija $tipKomunikacija) {
        
        $query = "select 
                    T.TipKomunikacijaID as tipKomunikacijaID,
                    T.Naziv as naziv,
                    case 
                        when T.Aktivan = 1 then 'true'
                        when T.Aktivan = 0 then 'false'
                    end as aktivan
                    from tipkomunikacija as T
                    where T.TipKomunikacijaID = ".$tipKomunikacija->getTipKomunikacijaID();
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
    
 
    public function TipKomunikacijaInsert (TipKomunikacija $tipKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($tipKomunikacija);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan tip komunikacija");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function TipKomunikacijaUpdate(TipKomunikacija $tipKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " TipKomunikacijaID = ".$tipKomunikacija->getGlasID();
        $result = $dbBroker->update($tipKomunikacija, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen tip komunikacija");
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
    public function TipKomunikacijaDelete(TipKomunikacija $tipKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " TipKomunikacijaID = ".$tipKomunikacija->getTipKomunikacijaID();
        $result = $dbBroker->delete($tipKomunikacija, $condition);
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
    
    //Funkcija za punjenje glasova
    public function TipKomunikacijaGetList(FilterTipKomunikacija $filter) {
        $query = "select 
                    T.TipKomunikacijaID,
                    T.Naziv,
                    case 
                        when T.Aktivan = 1 then 'Aktivan'
                        when T.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from tipkomunikacija as T
        where ('$filter->naziv' = '' or (T.Naziv like '%$filter->naziv%'))
        and ('$filter->aktivan' = '' or (T.Aktivan = '$filter->aktivan')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by T.Naziv asc ";
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

