<?php

class RadioStanicaProgramClass {
    public function RadioStanicaProgramLoad(RadioStanicaProgram $radioStanicaProgram) {     
        $query = "select 
                    P.RadioStanicaProgramID as radioStanicaProgramID,
                    P.RadioStanicaID as radioStanicaID,
                    P.Naziv as naziv,
                    substring(P.PocetakEmitovanja, 1, 5) as pocetakEmitovanja,
                    substring(P.KrajEmitovanja, 1, 5) as krajEmitovanja,
                    case 
                        when P.RadniDan = 1 then 'true'
                        when P.RadniDan = 0 then 'false'
                    end as radniDan,
                    case 
                        when P.Aktivan = 1 then 'true'
                        when P.Aktivan = 0 then 'false'
                    end as aktivan
                    from radiostanicaprogram as P
                    where P.RadioStanicaProgramID = ".$radioStanicaProgram->getRadioStanicaProgramID();
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
    
    public function RadioStanicaProgramInsert (RadioStanicaProgram $radioStanicaProgram) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($radioStanicaProgram);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana emisija");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function RadioStanicaProgramUpdate(RadioStanicaProgram $radioStanicaProgram) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RadioStanicaProgramID = ".$radioStanicaProgram->getRadioStanicaProgramID();
        $result = $dbBroker->update($radioStanicaProgram, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena emisija");
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
    public function RadioStanicaProgramDelete(RadioStanicaProgram $radioStanicaProgram) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RadioStanicaProgramID = ".$radioStanicaProgram->getRadioStanicaProgramID();
        $result = $dbBroker->delete($radioStanicaProgram, $condition);
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
    
    //Funkcija za punjenje cenovnika
    public function RadioStanicaProgramGetList(FilterRadioStanicaProgram $filter) {
        $query = "select 
                    P.RadioStanicaProgramID,
                    R.Naziv as RadioStanica,
                    P.Naziv,
                    substring(P.PocetakEmitovanja, 1, 5) as PocetakEmitovanja,
                    substring(P.KrajEmitovanja, 1, 5) as KrajEmitovanja,
                    case 
                        when P.RadniDan = 1 then 'radni dan'
                        when P.RadniDan = 0 then 'vikend'
                    end as TipDana,
                    case 
                        when P.Aktivan = 1 then 'true'
                        when P.Aktivan = 0 then 'false'
                    end as Aktivan
                    from radiostanicaprogram as P
                    inner join radiostanica R on P.RadioStanicaID = R.RadioStanicaID
                    where ($filter->radioStanicaID is null or (P.RadioStanicaID = $filter->radioStanicaID))";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by P.RadioStanicaID asc ";
        }
        $query .= $querySort;
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
    
    public function RadioStanicaProgramGetForComboBox($radioStationID) {
        $query = "select 
                    rp.RadioStanicaProgramID as EntryID,
                    rp.Naziv as EntryName
                    from radiostanicaprogram rp
                    where rp.RadioStanicaID = $radioStationID";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
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
}

?>
