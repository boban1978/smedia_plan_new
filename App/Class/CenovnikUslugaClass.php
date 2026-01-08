<?php

/**
 * Description of DelatnostClass
 *
 * @author n.lekic
 */
class CenovnikUslugaClass {

    public function CenovnikUslugaGetForComboBox(CenovnikUsluga $object) {
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

    public function CenovnikUslugaLoad(CenovnikUsluga $cenovnikUsluga) {
        $query = "select 
                    C.CenovnikUslugaID as cenovnikUslugaID,
                    C.Naziv as naziv,
                    C.Cena as cena
                    from cenovnikusluga as C
                    where C.CenovnikUslugaID = " . $cenovnikUsluga->getCenovnikUslugaID();
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

    public function CenovnikUslugaInsert(CenovnikUsluga $cenovnikUsluga) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($cenovnikUsluga);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana stavka cenovnika usluga");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function CenovnikUslugaUpdate(CenovnikUsluga $cenovnikUsluga) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " CenovnikUslugaID = " . $cenovnikUsluga->getCenovnikUslugaID();
        $result = $dbBroker->update($cenovnikUsluga, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena stavka cenovnika usluga");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija za brisanje 
    public function CenovnikUslugaDelete(CenovnikUsluga $cenovnikUsluga) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " CenovnikUslugaID = " . $cenovnikUsluga->getCenovnikUslugaID();
        $result = $dbBroker->delete($cenovnikUsluga, $condition);
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

    public function CenovnikUslugaGetList(FilterCenovnikUsluga $filter) {
        $query = "select 
                    C.CenovnikUslugaID,
                    C.Naziv,
                    C.Cena
                    from cenovnikusluga as C
        where ('$filter->nazivFilter' = '' or (C.Naziv like '%$filter->nazivFilter%')) ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by C.Naziv asc ";
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
