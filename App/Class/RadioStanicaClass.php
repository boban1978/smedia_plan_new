<?php

class RadioStanicaClass {
    public function RadioStanicaGetForComboBox(RadioStanica $radioStanica) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($radioStanica);
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
    
    public function RadioStanicaLoad(RadioStanica $radioStanica) {
        
        $query = "select 
                    R.RadioStanicaID as radioStanicaID,
                    R.Naziv as naziv,
                    R.Adresa as adresa,
                    case 
                        when R.Aktivan = 1 then 'true'
                        when R.Aktivan = 0 then 'false'
                    end as aktivan
                    from radiostanica R
                    where R.RadioStanicaID = ".$radioStanica->getRadioStanicaID();
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
    
 
    public function RadioStanicaInsert (RadioStanica $radioStanica) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($radioStanica);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana radio stanica");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function RadioStanicaUpdate(RadioStanica $radioStanica) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RadioStanicaID = ".$radioStanica->getRadioStanicaID();
        $result = $dbBroker->update($radioStanica, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjea radio stanica");
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
    public function RadioStanicaDelete(RadioStanica $radioStanica) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RadioStanicaID = ".$radioStanica->getRadioStanicaID();
        $result = $dbBroker->delete($radioStanica, $condition);
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
    public function RadioStanicaGetList(FilterRadioStanica $filter) {
        $query = "select 
                    R.RadioStanicaID,
                    R.Naziv,
                    R.Adresa,
                    R.Logo,
                    case 
                        when R.Aktivan = 1 then 'Aktivan'
                        when R.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from radiostanica as R
        where ('$filter->nazivFilter' = '' or (R.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->adresaFilter' = '' or (R.Adresa = '%$filter->adresaFilter%')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by R.Naziv asc ";
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
