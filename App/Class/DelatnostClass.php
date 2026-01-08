<?php

/**
 * Description of DelatnostClass
 *
 * @author n.lekic
 */
class DelatnostClass {

    public function DelatnostGetForComboBox(Delatnost $object) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($object);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('rows:' . json_encode($result['rows']));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        return $responseNew;
    }

    public function DelatnostLoad(Delatnost $delatnost) {
        $query = "select 
                    D.DelatnostID as delatnostID,
                    D.Naziv as naziv,
                    case 
                        when D.Aktivan = 1 then 'true'
                        when D.Aktivan = 0 then 'false'
                    end as aktivan
                    from delatnost as D
                    where D.DelatnostID = " . $delatnost->getDelatnostID();
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:' . json_encode($result));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function DelatnostInsert(Delatnost $delatnost) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($delatnost);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana delatnost");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function DelatnostUpdate(Delatnost $delatnost) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " DelatnostID = " . $delatnost->getDelatnostID();
        $result = $dbBroker->update($delatnost, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena delatnost");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija za brisanje 
    public function DelatnostDelete(Delatnost $delatnost) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " DelatnostID = " . $delatnost->getDelatnostID();
        $result = $dbBroker->delete($delatnost, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno obrisana stavka");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function DelatnostGetList(FilterDelatnost $filter) {
        $query = "select 
                    D.DelatnostID,
                    D.Naziv,
                    case 
                        when D.Aktivan = 1 then 'Aktivan'
                        when D.Aktivan = 0 then 'Neaktivan'
                    end as Aktivan
                    from delatnost as D
        where ('$filter->nazivFilter' = '' or (D.Naziv like '%$filter->nazivFilter%'))
        and ('$filter->aktivanFilter' = '' or (D.Aktivan = '$filter->aktivanFilter')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by D.Naziv asc ";
        }
        $query .= $querySort;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('rows:' . json_encode($result['rows']) . ', total:' . $result['numRows']);
            }
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

}

?>
