<?php
class FinansijskiStatusKampanjaClass {
    
    public function FinansijskiStatusGetForComboBox(FinansijskiStatusKampanja $finansijskiStatusKampanja) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($finansijskiStatusKampanja);
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
    
    public function FinansijskiStatusLoad(FinansijskiStatusKampanja $finansijskiStatusKampanja) {
        $query = "select 
                    FSK.FinansijskiStatusKampanjaID as finansijskiStatusKampanjaID,
                    FSK.Naziv as naziv,
                    case 
                        when FSK.Aktivan = 1 then 'true'
                        when FSK.Aktivan = 0 then 'false'
                    end as aktivan
                    from finansijskistatuskampanja as FSK
                    where FSK.finansijskiStatusKampanjaID = ".$finansijskiStatusKampanja->getFinansijskiStatusKampanjaID();
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
    
    public function FinansijskiStatusInsert(FinansijskiStatusKampanja $finansijskiStatusKampanja){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($finansijskiStatusKampanja);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan finansijski status kampanje");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function FinansijskiStatusUpdate(FinansijskiStatusKampanja $finansijskiStatusKampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " FinansijskiStatusKampanjaID = ".$finansijskiStatusKampanja->getFinansijskiStatusKampanjaID();
        $result = $dbBroker->update($finansijskiStatusKampanja, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen finansijski status kampanje");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function FinansijskiStatusDelete(FinansijskiStatusKampanja $finansijskiStatusKampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " FinansijskiStatusKampanjaID = ".$finansijskiStatusKampanja->getFinansijskiStatusKampanjaID();
        $result = $dbBroker->delete($finansijskiStatusKampanja, $condition);
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

    public function FinansijskiStatusGetList(FilterFinansijskiStatusKampanja $filter) {
        $query = "select 
                    FSK.FinansijskiStatusKampanjaID,
                    FSK.Naziv,
                    case 
                        when FSK.Aktivan = 1 then 'Aktivan'
                        when FSK.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from finansijskistatuskampanja as FSK
        where ('$filter->nazivFilter' = '' or (FSK.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (FSK.Aktivan = '$filter->aktivanFilter')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by FSK.Naziv asc ";
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
}

?>
