<?php
/**
 * Description of PermisijaClass
 *
 * @author n.lekic
 */
class PermisijaClass {
    public function PermisijaGetForComboBox(Permisija $object) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox_my($object);//zbog sorta po id
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
    
    /**
     * Funkcija koja dohvata sve permisije vezane za Korisnika
     */
    public function PermisijaGetForUser($userID) {
        $query = "select R.PermisijaID 
                    from korisnikrola K
                    inner join rolapermisija R on K.RolaID = R.RolaID
                    where KorisnikID = $userID
                    order by R.PermisijaID";
        $dbBroker = new CoreDBBroker();
        $resultPermisijaList = $dbBroker->selectManyRows($query);
        $permisijaList = "[";
        for ($i = 0; $i < count($resultPermisijaList['rows']); $i++) {
            $permisijaList .= $resultPermisijaList['rows'][$i]['PermisijaID'];
            $permisijaList .= ",";
        }
        $permisijaList = count($resultPermisijaList['rows'])>0 ? substr($permisijaList, 0, strlen($permisijaList)-1): $permisijaList;
        $permisijaList .= "]";
        $result['permissions'] = $permisijaList;
        $responseNew = new CoreAjaxResponseInfo();
        if ($resultPermisijaList || $resultPermisijaList === 0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                $responseNew->SetData('data:'.json_encode($result));
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
