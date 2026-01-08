<?php

class BlokClass {
    public function BlokGetForComboBox(Blok $blok) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($blok);
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
    
    public function BlokLoad(Blok $blok) {
        $query = "select 
                    B.BlokID as blokID,
                    B.Sat as sat,
                    B.RedniBrojSat as redniBrojSat,
                    case 
                        when B.Vrsta = 0 then 'Obican'
                        when B.Vrsta = 1 then 'Premijum'
                    end as vrsta,
                    SUBSTRING(B.VremeStart, 4, 2) as vremeStartMin,
                    SUBSTRING(B.VremeStart, 7, 2) as vremeStartSec,
                    SUBSTRING(B.VremeEnd, 4, 2) as vremeEndMin,
                    SUBSTRING(B.VremeEnd, 7, 2) as vremeEndSec,
                    B.Trajanje as trajanje,
                    case 
                        when B.Aktivan = 1 then 'true'
                        when B.Aktivan = 0 then 'false'
                    end as aktivan
                    from blok as B
                    where B.blokID = ".$blok->getBlokID();
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
    
 
    public function BlokInsert (Blok $blok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($blok);            
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
    
    public function BlokUpdate(Blok $blok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BlokID = ".$blok->getBlokID();
        $result = $dbBroker->update($blok, $condition);
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
    public function BlokDelete(Blok $blok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BlokID = ".$blok->getBlokID();
        $result = $dbBroker->delete($blok, $condition);
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
    
    //Funkcija za punjenje klijenta
    public function BlokGetList(FilterBlok $filter) {
        $query = "select 
                    B.BlokID,
                    B.Sat,
                    B.RedniBrojSat,
                    case 
                        when B.Vrsta = 1 then 'Premijum'
                        when B.Vrsta = 0 then 'Običan'
                    end as Vrsta,
                    B.VremeStart,
                    B.VremeEnd,
                    B.Trajanje,
                    case 
                        when B.Aktivan = 1 then 'Aktivan'
                        when B.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from blok as B
        ";//order by B.Sat, B.RednibrojSat asc


        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by B.Sat, B.RednibrojSat asc  ";
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
