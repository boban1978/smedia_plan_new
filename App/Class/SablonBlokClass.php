<?php
/**
 * Description of SablonBlokClass
 *
 * @author n.lekic
 */
class SablonBlokClass {
    public function SablonBlokGetForComboBox(SablonBlok $sablonBlok) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($sablonBlok);
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
    
    public function SablonBlokLoad(SablonBlok $sablonBlok) {
        
        $query = "select
                    S.SablonBlokID as sablonBlokID,
                    S.SablonID as sablonID,
                    S.BlokID as blokID,
                    S.Datum as datum
                    where S.SablonBlokID = ".$sablonBlok->getSablonBlokID();
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
    
 
    public function SablonBlokInsert (SablonBlok $sablonBlok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($sablonBlok);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovab SablonBlok");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function SablonBlokUpdate(SablonBlok $sablonBlok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonBlokID = ".$sablonBlok->getSablonBlokID();
        $result = $dbBroker->update($sablonBlok, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen šablon blok");
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
    public function SablonBlokDelete(SablonBlok $sablonBlok) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonBlokID = ".$sablonBlok->getSablonBlokID();
        $result = $dbBroker->delete($sablonBlok, $condition);
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
    
    //Funkcija za punjenje grida sa sablonima
    public function SablonGetList(FilterSablonBlok $filter) {
        $query = "select
                    S.SablonBlokID as sablonBlokID,
                    S.SablonID as sablonID,
                    S.BlokID as blokID,
                    S.Datum as datum
        where ('$filter->naziv' = '' or (S.Naziv like '%$filter->naziv%'))
        and ('$filter->aktivan' = '' or (S.Aktivan = '$filter->aktivan'))";
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
