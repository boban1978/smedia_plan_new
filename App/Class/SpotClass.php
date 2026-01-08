<?php

class SpotClass {
    
    public function GetSpotDetails ($spotID) {
        $query = "select 
                    S.SpotID as spotID,
                    S.SpotLink as spotLink,
                    S.GlasID as glasID,
                    S.SpotTrajanje as trajanje
                    from spot as S
                    where S.spotID = " . $spotID;
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectOneRow($query);
        return $result;
    }

    public function SpotLoad(Spot $spot) {
        $result = $this->GetSpotDetails($spot->getSpotID());
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData('data:' . json_encode($result));
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        //$dbBroker->close();
        return $responseNew;
    }

    public function SpotGetForComboBox(Spot $spot) {
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->getDataForComboBox($spot);
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

    public function SpotInsert(Spot $spot) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($spot);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovan spot");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function SpotUpdate(Spot $spot) {

        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();



        $spotID=0+$spot->getSpotID();

        if ($spotID>0) {
            $condition = " SpotID = " . $spot->getSpotID();
        } else {
            $condition = " KampanjaID = " . $spot->getKampanjaID();
        }
        $result = $dbBroker->update($spot, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen spot");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function SpotDelete(Spot $spot) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SpotID = " . $spot->getSpotID();
        $result = $dbBroker->delete($spot, $condition);
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

    public function SpotGetList(FilterSpot $filter) {
        $query = "select 
                    S.SpotID as spotID,
                    K.Naziv as Kampanja,
                    S.SpotLink as spotLink,
                    G.ImePrezime as Glas,
                    S.Trajanje as trajanje
                    from spot as S
                    inner join kampanja K on S.KampanjaID = K.KampanjaID
                    inner join glas G on S.GlasID = G.GlasID  ";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by K.Naziv asc ";
        }
        $query .= $querySort;
        //$query .= "LIMIT $filter->start, $filter->limit";
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



    public function SpotDeleteFile($spotName,$radioStanica) {

        $file_path = "/home/SHARING/STREAMING/Emitovanje/Reklame/" . strtolower($radioStanica) . "/".$spotName.".mp3";
        @unlink($file_path);

        $file_path = "/home/SHARING/STREAMING/Emitovanje/Reklame/" . strtolower($radioStanica) . "/".$spotName.".wav";
        @unlink($file_path);

        $responseNew = new CoreAjaxResponseInfo();
        $responseNew->SetSuccess('true');
        //$responseNew->SetMessage($file_path);

        return $responseNew;
    }




}

?>
