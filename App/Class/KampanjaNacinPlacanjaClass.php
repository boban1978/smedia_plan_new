<?php
/**
 * Description of KampanjaNacinPlacanjaClass
 *
 * @author n.lekic
 */
class KampanjaNacinPlacanjaClass {
    public function KampanjaNacinPlacanjaForComboBox(KampanjaNacinPlacanja $object) {
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
    
    public function KampanjaNacinPlacanjaLoad(KampanjaNacinPlacanja $kampanjaNacinPlacanja) {
        $query = "select 
                    K.KampanjaNacinPlacanjaID as kampanjaNacinPlacanjaID,
                    K.Naziv as naziv,
                    case 
                        when K.Aktivan = 1 then 'true'
                        when K.Aktivan = 0 then 'false'
                    end as aktivan
                    from kampanjanacinplacanja as K
                    where K.KampanjaNacinPlacanjaID = ".$kampanjaNacinPlacanja->getKampanjaNacinPlacanjaID();
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
    
    public function KampanjaNacinPlacanjaInsert(KampanjaNacinPlacanja $kampanjaNacinPlacanja){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($kampanjaNacinPlacanja);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan nacin placanja");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function KampanjaNacinPlacanjaUpdate(KampanjaNacinPlacanja $kampanjaNacinPlacanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KampanjaNacinPlacanjID = ".$kampanjaNacinPlacanja->getKampanjaNacinPlacanjaID();
        $result = $dbBroker->update($kampanjaNacinPlacanja, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen nacin placanja");
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
    public function KampanjaNacinPlacanjaDelete(KampanjaNacinPlacanja $kampanjaNacinPlacanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " KampanjaNacinPlacanjaID = ".$kampanjaNacinPlacanja->getKampanjaNacinPlacanjaID();
        $result = $dbBroker->delete($kampanjaNacinPlacanja, $condition);
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
    

    public function KampanjaNacinPlacanjaGetList(FilterKampanjaNacinPlacanja $filter) {
        $query = "select 
                    K.KampanjaNacinPlacanjaID,
                    K.Naziv,
                    case 
                        when K.Aktivan = 1 then 'Aktivan'
                        when K.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from kampanjanacinplacanja as K
        where ('$filter->nazivFilter' = '' or (K.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (K.Aktivan = '$filter->aktivanFilter')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.Naziv asc ";
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
