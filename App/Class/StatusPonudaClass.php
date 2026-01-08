<?php
class StatusPonudaClass {
    
    public function StatusPonudaGetForComboBox(StatusPonuda $statusPonuda) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($statusPonuda);
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
    
    public function StatusPonudaLoad(StatusPonuda $statusPonuda) {
        $query = "select 
                    SP.StatusPonudaID as statusPonudaID,
                    SP.Naziv as naziv,
                    case 
                        when SP.Aktivan = 1 then 'true'
                        when SP.Aktivan = 0 then 'false'
                    end as aktivan
                    from statusponuda as SP
                    where SP.statusPonudaID = ".$statusPonuda->getStatusPonudaID();
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
    
    public function StatusPonudaInsert(StatusPonuda $statusPonuda){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($statusPonuda);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan status ponude");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function StatusPonudaUpdate(StatusPonuda $statusPonuda) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " StatusPonudaID = ".$statusPonuda->getStatusPonudaID();
        $result = $dbBroker->update($statusPonuda, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen status ponuda");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function StatusPonudaDelete(StatusPonuda $statusPonuda) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " StatusPonudaID = ".$statusPonuda->getStatusPonudaID();
        $result = $dbBroker->delete($statusPonuda, $condition);
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

    public function StatusPonudaGetList(FilterStatusPonuda $filter) {
        $query = "select 
                    SP.StatusPonudaID,
                    SP.Naziv,
                    case 
                        when SP.Aktivan = 1 then 'Aktivan'
                        when SP.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from statusponuda as SP
        where ('$filter->nazivFilter' = '' or (SP.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (SP.Aktivan = '$filter->aktivanFilter')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by SP.Naziv asc ";
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
