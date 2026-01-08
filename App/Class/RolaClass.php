<?php

/**
 * Description of RolaClass
 *
 * @author n.lekic
 */
class RolaClass {
    
    public function RolaGetForComboBox(Rola $object) {
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
    
    public function RolaLoad(Rola $rola) {
        $query = "select 
                    R.RolaID as rolaID,
                    R.Naziv as naziv,
                    R.Opis as opis,
                    case 
                        when R.Aktivan = 1 then 'true'
                        when R.Aktivan = 0 then 'false'
                    end as aktivan
                    from rola as R
                    where R.rolaID = ".$rola->getRolaID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        //Deo za dohavtanje podataka o listi rola
        $queryPermisijaList = "select PermisijaID from rolapermisija where RolaID = ".$rola->getRolaID();
        $resultPermisijaList = $dbBroker->selectManyRows($queryPermisijaList);
        $permisijaList= "[";
        for ($i = 0; $i < count($resultPermisijaList['rows']); $i++) {
            $permisijaList .= $resultPermisijaList['rows'][$i]['PermisijaID'];
            $permisijaList .= ",";
        }
        $permisijaList = count($resultPermisijaList['rows'])>0 ? substr($permisijaList, 0, strlen($permisijaList)-1): $permisijaList;
        $permisijaList .= "]";
        $result['permisijaList'] = $permisijaList;
        
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
    
    public function RolaInsert(Rola $rola){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($rola);   
        $rolaID = $dbBroker->getLastInsertedId();
        //$userID = "Rezultat ovog IDa zadnjeg insertovanog";
        $permisijaList = $rola->getPermisijaList();
        $result1 = true;
        for ($i = 0; $i < count($permisijaList); $i++) {
            $rolaPermisija = new RolaPermisija();
            $rolaPermisija->setRolaID($rolaID);
            $rolaPermisija->setPermisijaID($permisijaList[$i]);
            $rolaPermisija->setAktivan('true');
            $result1 = $dbBroker->insert($rolaPermisija);
            $result1 &=$result1;
        }
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana rola");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function RolaUpdate(Rola $rola) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RolaID = ".$rola->getRolaID();
        $result = $dbBroker->update($rola, $condition);
        
        $rolaPermisija = new RolaPermisija();
        $result1 = $dbBroker->delete($rolaPermisija, $condition);
        
        $permisijaList = $rola->getPermisijaList();
        $result2 = true;
        for ($i = 0; $i < count($permisijaList); $i++) {
            $rolaPermisija = new RolaPermisija();
            $rolaPermisija->setRolaID($rola->getRolaID());
            $rolaPermisija->setPermisijaID($permisijaList[$i]);
            $rolaPermisija->setAktivan('true');
            $result2 = $dbBroker->insert($rolaPermisija);
            $result2 &=$result2;
        }
        
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1 && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena rola");
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
    public function RolaDelete(Rola $rola) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " RolaID = ".$rola->getRolaID();
        
        //Brisanje svih permisija prvo
        $rolaPermisija = new RolaPermisija();
        $result1 = $dbBroker->delete($rolaPermisija, $condition);
        
        $result = $dbBroker->delete($rola, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result && $result1 ) {
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
    
    public function RolaGetList(FilterRola $filter) {
        $query = "select 
                    distinct
                    R.RolaID,
                    R.Naziv,
                    R.Opis,
                    case 
                        when R.Aktivan = 1 then 'Aktivan'
                        when R.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from rola as R
                    left outer join rolapermisija RP on R.RolaID = RP.RolaID
        where ('$filter->nazivFilter' = '' or (R.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (R.Aktivan = '$filter->aktivanFilter')) 
        and ($filter->rolaPrivilegijaFilterID is null or (RP.PermisijaID = $filter->rolaPrivilegijaFilterID)) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by R.Naziv asc ";
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
