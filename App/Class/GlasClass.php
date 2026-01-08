<?php

class GlasClass {
    public function GlasGetForComboBox(Glas $glas) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($glas);
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
    
    public function GlasLoad(Glas $glas) {
        
        $query = "select 
                    G.GlasID as glasID,
                    G.ImePrezime as imePrezime,
                    case 
                        when G.Aktivan = 1 then 'true'
                        when G.Aktivan = 0 then 'false'
                    end as aktivan
                    from glas as G
                    where G.glasID = ".$glas->getGlasID();
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
    
 
    public function GlasInsert (Glas $glas) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($glas);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan glas");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function GlasUpdate(Glas $glas) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " GlasID = ".$glas->getGlasID();
        $result = $dbBroker->update($glas, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen glas");
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
    public function GlasDelete(Glas $glas) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " GlasID = ".$glas->getGlasID();
        $result = $dbBroker->delete($glas, $condition);
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
    public function GlasGetList(FilterGlas $filter) {
        $query = "select 
                    G.GlasID,
                    G.ImePrezime,
                    case 
                        when G.Aktivan = 1 then 'Aktivan'
                        when G.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from glas as G
        where ('$filter->imePrezimeFilter' = '' or (G.ImePrezime like '%$filter->imePrezimeFilter%'))
        and ('$filter->aktivan' = '' or (G.Aktivan = '$filter->aktivan')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by G.ImePrezime asc ";
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
