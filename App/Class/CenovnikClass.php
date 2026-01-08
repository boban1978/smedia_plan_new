<?php

class CenovnikClass {
    public function CenovnikLoad(Cenovnik $cenovnik) {     
        $query = "select 
                    C.CenovnikID as cenovnikID,
                    C.RadioStanicaID as radioStanicaID,
                    C.BlokID as blokID,
                    C.KategorijaCenaID as kategorijaCenaID,
                    C.Cena as cena,
                    case 
                        when C.Vikend = 1 then 'true'
                        when C.Vikend = 0 then 'false'
                    end as vikend,
                    case 
                        when C.Aktivan = 1 then 'true'
                        when C.Aktivan = 0 then 'false'
                    end as aktivan
                    from cenovnik as C
                    where C.CenovnikID = ".$cenovnik->getCenovnikID();
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
    
    public function CenovnikInsert (Cenovnik $cenovnik) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($cenovnik);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan blok");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function CenovnikUpdate(Cenovnik $cenovnik) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " CenovnikID = ".$cenovnik->getCenovnikID();
        $result = $dbBroker->update($cenovnik, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen blok");
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
    public function CenovnikDelete(Cenovnik $cenovnik) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " CenovnikID = ".$cenovnik->getCenovnikID();
        $result = $dbBroker->delete($cenovnik, $condition);
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
    public function CenovnikGetList(FilterCenovnik $filter) {
        $query = "select 
                    C.CenovnikID,
                    R.Naziv as RadioStanica,
                    concat_ws(' ', 'Sat', B.Sat, 'Rb', B.RedniBrojSat) as Blok,
                    KC.Naziv as Kategorija,
                    C.Cena,
                    case 
                        when C.Vikend = 1 then 'Vikend'
                        when C.Vikend = 0 then 'Radni dan'
                    end as Aktivan,
                    case 
                        when C.Aktivan = 1 then 'Aktivan'
                        when C.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from cenovnik as C
                    inner join radiostanica R on C.RadioStanicaID = R.RadioStanicaID
                    inner join blok as B on C.BlokID = B.BlokID
                    inner join kategorijacena KC on C.KategorijaCenaID = KC.KategorijaCenaID 
                    ";


        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by B.Sat, B.RednibrojSat asc   ";
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
    
    public function KategorijaCenaGetForComboBox(KategorijaCena $kategorijaCena) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($kategorijaCena);
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
