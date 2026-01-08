<?php
class IstorijaKomunikacijaClass {
    public function IstorijaKomunikacijaLoad(IstorijaKomunikacija $istorijaKomunikacija) {
        $query = "select 
                    IK.IstorijaKomunikacijaID as istorijaKomunikacijaID,
                    IK.KlijentID as klijentID,
                    IK.KorisnikID as korisnikID,
                    IK.TipKomunikacijaID as tipKomunikacijaID,
                    IK.DatumKomunikacije as datumKomunikacije,
                    IK.ZaveoID as zaveoID,
                    IK.VremePostavke as vremePostavke,
                    IK.Napomena as napomena,
                    IK.Prilog as prilog
                    from istorijakomunikacija as IK
                    where ID.istorijaKomunikacijaID = ".$istorijaKomunikacija->getIstorijaKomunikacijaID();
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
    
//    public function IstorijaKomunikacijaGetForComboBox(IstorijaKomunikacija $istorijaKomunikacija) {
//        $dbBroker = new CoreDBBroker();
//        $result = $dbBroker->getDataForComboBox($istorijaKomunikacija);
//        $responseNew = new CoreAjaxResponseInfo();
//        if ($result) {
//            $responseNew->SetSuccess('true');
//            $responseNew->SetData('rows:'.json_encode($result['rows']));
//        }
//        else {
//            $responseNew->SetSuccess('false');
//            $responseNew->SetMessage(CoreError::getError());
//        }
//        return $responseNew;
//    }
    
    public function IstorijaKomunikacijaInsert (IstorijaKomunikacija $istorijaKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($istorijaKomunikacija);            
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana istorija komunikacije");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function IstorijaKomunikacijaUpdate(IstorijaKomunikacija $istorijaKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " IstorijaKomunikacijaID = ".$istorijaKomunikacija->getIstorijaKomunikacijaID();
        $result = $dbBroker->update($istorijaKomunikacija, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjena istorija komunikacije");
        }
        else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }
    
    public function IstorijaKomunikacijaDelete(IstorijaKomunikacija $istorijaKomunikacija) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " IstorijaKomunikacijaID = ".$istorijaKomunikacija->getIstorijaKomunikacijaID();
        $result = $dbBroker->delete($istorijaKomunikacija, $condition);
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
    
    public function IstorijaKomunikacijaGetList(FilterIstorijaKomunikacija $filter) {
        $query = "SELECT
                    I.IstorijaKomunikacijaID,
                    DATE_FORMAT(I.DatumKomunikacije,'%d.%m.%Y') as Datum,
                    concat_ws(' ', K.Ime, K.Prezime) as Uneo,
                    TK.Naziv as TipKomunikacija,
                    I.Napomena as Sadrzaj,
                    REPLACE(I.PrilogLink, '../../', '../') as Link
                    FROM istorijakomunikacija I
                    inner join korisnik K on I.ZaveoID = K.KorisnikID
                    left outer join tipkomunikacija TK on I.TipKomunikacijaID = TK.TipKomunikacijaID
                    where ($filter->klijentID is null or (I.KlijentID = $filter->klijentID))  ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by I.VremePostavke asc ";
        }
        $query .= $querySort;
        //$query .= "LIMIT $filter->start, $filter->limit";
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query, $filter->start, $filter->limit);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result===0) {
            $responseNew->SetSuccess('true');
            if ($result <> 0) {
                /*
                foreach($result['rows'] as $key => $row){
                    $result['rows'][$key]['Link']=str_replace("../../","../",$result['rows'][$key]['Link']);
                }*/
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
