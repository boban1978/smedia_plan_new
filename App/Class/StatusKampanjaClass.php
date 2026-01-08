<?php
/**
 * Description of StatusKampanjaClass
 *
 * @author n.lekic
 */
class StatusKampanjaClass {
    public function StatusKampanjaForComboBox(StatusKampanja $object) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($object);
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
    
    public function StatusKampanjaLoad(StatusKampanja $statusKampanja) {
        $query = "select 
                    S.StatusKampanjaID as statusKampanjaID,
                    S.Naziv as naziv,
                    case 
                        when S.Aktivan = 1 then 'true'
                        when S.Aktivan = 0 then 'false'
                    end as aktivan
                    from statuskampanja as S
                    where S.StatusKampanjaID = ".$statusKampanja->getStatusKampanjaID();
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
    
    public function StatusKampanjaInsert(StatusKampanja $statusKampanja){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($statusKampanja);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan status kampanje");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function StatusKampanjaUpdate(StatusKampanja $statusKampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " StatusKampanjaID = ".$statusKampanja->getStatusKampanjaID();
        $result = $dbBroker->update($statusKampanja, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen status kampanje");
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
    public function StatusKampanjaDelete(StatusKampanja $statusKampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " StatusKampanjaID = ".$statusKampanja->getStatusKampanjaID();
        $result = $dbBroker->delete($statusKampanja, $condition);
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
    

    public function StatusKampanjaGetList(FilterStatusKampanja $filter) {
        $query = "select 
                    S.StatusKampanjaID,
                    S.Naziv,
                    case 
                        when S.Aktivan = 1 then 'Aktivan'
                        when S.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from statuskampanja as S
        where ('$filter->nazivFilter' = '' or (S.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (S.Aktivan = '$filter->aktivanFilter')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by S.Naziv asc ";
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
