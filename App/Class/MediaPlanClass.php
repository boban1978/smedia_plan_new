<?php

class MediaPlanClass {

    public function MediaPlanLoadNew($startDate, $endDate, $radioStanicaID = 'null') {
        $dbBroker = new CoreDBBroker();
        $query = "SELECT bz.*, b.Sat, b.RedniBrojSat, b.Trajanje
                    FROM blokzauzece bz
                    INNER JOIN blok b
                    ON bz.BlokID = b.BlokID
                    WHERE (bz.Datum between '" . $startDate . "' and '" . $endDate . "') 
                    and ({$radioStanicaID} = null or bz.RadioStanicaID = {$radioStanicaID})
                    ORDER BY bz.BlokID";
        $result = $dbBroker->selectManyRows($query);
        $response = new stdClass();
        //$data = new stdClass();
        $i = 1;
        $evts = array();
        foreach ($result['rows'] as $row) {
            //echo $row['BlokID'].'<br>';
            // {"id":1 , "cid": 1 , "title":"Blok1 - Zauzeto 20/150 sec" , "start": "2012-05-21 08:00:00" , "end": "2012-05-21 08:30:00"},
            if (strlen(trim($row['Sat'])) < 2) {
                $sat = '0' . trim($row['Sat']);
            } else {
                $sat = trim($row['Sat']);
            }
            $zauzeto = ($row['ZauzetoSekundi'] / $row['Trajanje']) * 100;
            if ($zauzeto < 25) {
                $cid = 1;
            } elseif ($zauzeto > 75) {
                $cid = 3;
            } else {
                $cid = 2;
            }
            $rowEvts['id'] = $i;
            $rowEvts['cid'] = $cid;
            $rowEvts['loc'] = $row['BlokID'];
            $rowEvts['title'] = "Blok " . $row['RedniBrojSat'] . " - Zauzeto " . $row['ZauzetoSekundi'] . "/" . $row['Trajanje'] . " sec";
            $rowEvts['start'] = date("Y-m-d", strtotime($startDate)) . " " . $sat . ":00:00";
            $rowEvts['end'] = date("Y-m-d", strtotime($endDate)) . " " . $sat . ":30:00";
            $evts[] = $rowEvts;
            $i++;
        }

        if ($result) {

            $response->success = true;
            $response->evts = $evts;
        } else {
            $response->success = false;
            $response->msg = CoreError::getError();
        }
        $dbBroker->close();
        return json_encode($response);
    }

    public function MediaPlanLoad($startDate, $endDate, $radioStanicaID = 'null') {
        $dbBroker = new CoreDBBroker();
        $query = "SELECT bz.*, b.Sat, b.RedniBrojSat, b.Trajanje
                    FROM blokzauzece bz
                    INNER JOIN blok b
                    ON bz.BlokID = b.BlokID
                    WHERE (bz.Datum between '" . $startDate . "' and '" . $endDate . "') 
                    and ({$radioStanicaID} = null or bz.RadioStanicaID = {$radioStanicaID})
                    ORDER BY bz.BlokID";
        $result = $dbBroker->selectManyRows($query);
        $i = 1;
        $evts = 'evts:[';
        foreach ($result['rows'] as $row) {
            //echo $row['BlokID'].'<br>';
            // {"id":1 , "cid": 1 , "title":"Blok1 - Zauzeto 20/150 sec" , "start": "2012-05-21 08:00:00" , "end": "2012-05-21 08:30:00"},
            if (strlen(trim($row['Sat'])) < 2) {
                $sat = '0' . trim($row['Sat']);
            } else {
                $sat = trim($row['Sat']);
            }
            $zauzeto = ($row['ZauzetoSekundi'] / $row['Trajanje']) * 100;
            if ($zauzeto < 25) {
                $cid = 1;
            } elseif ($zauzeto > 75) {
                $cid = 3;
            } else {
                $cid = 2;
            }
            $evts .= '{"id":' . $i . ',"cid":' . $cid . ', "loc":' . $row['BlokID'] . ', "title":"Blok ' . $row['RedniBrojSat'] . ' - Zauzeto ' . $row['ZauzetoSekundi'] . '/' . $row['Trajanje'] . ' sec","start":"' . date("Y-m-d", strtotime($startDate)) . ' ' . $sat . ':00:00","end":"' . date("Y-m-d", strtotime($endDate)) . ' ' . $sat . ':30:00"},';
            $i++;
        }
        $evts = substr($evts, 0, strlen($evts) - 1);
        $evts .= ']';
        //file_put_contents('evts.txt', $evts);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData($evts);
            //$data = '{success:true ,'.$evts.'}';
            //return $data;
        } else {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            //$data = '{success:false , msg:'.CoreError::getError().'}';
            //return $data;
        }
        $dbBroker->close();
        return $responseNew;
    }

}

?>
