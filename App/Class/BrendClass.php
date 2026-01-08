<?php

/**
 * Description of BrendClass
 *
 * @author n.lekic
 */
class BrendClass {
    
    public function BrendGetForComboBox($clientID) {
        $query = "select 
                    b.BrendID as EntryID,
                    b.Naziv as EntryName
                    from brend b
                    where b.KlijentID = $clientID";
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
    
    public function BrendLoad(Brend $brend) {
        $query = "select 
                    B.BrendID as brendID,
                    B.Naziv as naziv,
                    B.KlijentID as klijentID,
                    B.DelatnostID as delatnostID
                    from brend as B
                    where B.BrendID = ".$brend->getBrendID();
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
    
    public function BrendInsert(Brend $brend){
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($brend);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan brend");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function BrendUpdate(Brend $brend) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BrendID = ".$brend->getBrendID();
        $result = $dbBroker->update($brend, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen brend");
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
    public function BrendDelete(Brend $brend) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " BrendID = ".$brend->getBrendID();
        $result = $dbBroker->delete($brend, $condition);
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

    public function BrendGetList($klijentID) {
        $query = "select 
                    B.BrendID,
                    B.KlijentID,
                    B.DelatnostID,
                    B.Naziv,
                    K.Naziv as Klijent,
                    D.Naziv as Delatnost
                    from brend as B
                    inner join klijent K on B.KlijentID = K.KlijentID
                    inner join delatnost D on B.DelatnostID = D.DelatnostID
        where B.KlijentID = $klijentID ";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
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
