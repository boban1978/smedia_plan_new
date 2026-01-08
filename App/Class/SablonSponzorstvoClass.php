<?php

class SablonSponzorstvoClass {

    public function SablonSponzorstvoLoad(SablonSponzorstvo $sablonSponzorstvo) {
        $query = "select  SablonSponzorstvoID as sablonSponzorstvoID,
                 KlijentID as klijentID,
                 RadioStanicaID as radioStanicaID,
                 RadioStanicaProgramID as radioStanicaProgramID,
                 DatumOd as datumOd,
                 DatumDo as datumDo,
                 CenaUkupno as cenaUkupno,
                 Cobrending as cobrending,
                 Najava as najava,
                 Odjava as odjava,
                 Prsegment as prsegment,
                 PremiumBlok as premiumBlok,
                 KorisnikID as korisnikID,
                 VremePostavke as vremePostavke
                 from sablonsponzorstvo
                 where SablonSponzorstvoID = " . $sablonSponzorstvo->getSablonSponzorstvoID();
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

    public function SablonSponzorstvoInsert(SablonSponzorstvo $sablonSponzorstvo) {
        $dbBroker = new CoreDBBroker();
        $responseNew = new CoreAjaxResponseInfo();
        $dbBroker->beginTransaction();
        $newKampanja = new Kampanja();
        $newKampanja->setKlijentID($sablonSponzorstvo->getKlijentID());
        $newKampanja->setNaziv('Sponzorstvo');
        $newKampanja->setDatumPocetka($sablonSponzorstvo->getDatumOd());
        $newKampanja->setDatumKraja($sablonSponzorstvo->getDatumDo());
        $newKampanja->setKorisnikID($sablonSponzorstvo->getKorisnikID());
        $newKampanja->setFinansijskiStatusID(FinansijskiStatus::Nefakturisana);
        $newKampanja->setStatusKampanjaID(StatusKampanjaEnum::Uneta);
        $newKampanja->setBudzet($sablonSponzorstvo->getCenaUkupno());
        $newKampanja->setRadioStanicaID($sablonSponzorstvo->getRadioStanicaID());
        $newKampanja->setCenaUkupno($sablonSponzorstvo->getCenaUkupno());
        $result0 = $dbBroker->insert($newKampanja);
        //Deo gde ćemo vezati spot za konačnu kampanju
        $kampanjaID = $dbBroker->getLastInsertedId();
        
        
        
        $result = $dbBroker->insert($sablonSponzorstvo);
//Dohvatamo podatke o emisiji koja se emituje
        $query1 = "SELECT PocetakEmitovanja,
                   KrajEmitovanja,
                   RadniDan
                   FROM radiostanicaprogram 
                   WHERE RadioStanicaProgramID = " . $sablonSponzorstvo->getRadioStanicaProgramID();
        $result1 = $dbBroker->selectOneRow($query1);
        $satOd = $result1['PocetakEmitovanja'];
        $satDo = $result1['KrajEmitovanja'];
        $radniDan = $result1['RadniDan'];
//pravimo niz datuma koji će biti uključeni u sponzorstvo
        $now = date('Y-m-d', time());
        $startDate = $sablonSponzorstvo->getDatumOd();
        $endDate = $sablonSponzorstvo->getDatumDo();
        if (strtotime($startDate) < strtotime($now)) {
            $startDate = $now;
        }
        if (strtotime($endDate) < strtotime($now)) {
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage("Datum kraja sponzorstva je manji od trenutnog datuma");
            return $responseNew;
        }

        $daniZaKampanju = array();
        $start = $startDate;
        $i = 0;
        if (strtotime($startDate) <= strtotime($endDate)) {
            while (strtotime($start) <= strtotime($endDate)) {
                //Gledamo numeričkureprezentaciju dana radi vikenda ili radnog dana
                $day = date("N", strtotime($start));
                
                if (($radniDan && in_array($day, array(1, 2, 3, 4, 5))) || (!$radniDan && in_array($day, array(6, 7)))) {
                    $daniZaKampanju[] = $start;
                }
                $start = date('Y-m-d', strtotime($startDate . '+' . $i . ' days'));
                $i++;
            }
        }
        $daniZaKampanju = array_unique($daniZaKampanju);
        //Pravimo niz običnih i niz premium blokova koji se nalaze u periodu trajanja emisije
        $blokPremium = array();
        $blokObican = array();
        $query2 = "SELECT * FROM blok
                    where VremeStart > '$satOd' and VremeEnd < '$satDo' order by BlokID asc";
        $result2 = $dbBroker->selectManyRows($query2);
        foreach ($result2['rows'] as $row) {
            if ($row['Vrsta'] == 1) {
                array_push($blokPremium, $row['BlokID']);
            } else {
                array_push($blokObican, $row['BlokID']);
            }
        }

        $cobrendingFrequency = round(count($blokObican) / $sablonSponzorstvo->getCobrending(), 0, PHP_ROUND_HALF_DOWN);
        $najavaOdjavaFrequency = round(count($blokObican) / $sablonSponzorstvo->getNajavaOdjava(), 0, PHP_ROUND_HALF_DOWN);
        $najavaEmisijeFrequency = round(count($blokObican) / $sablonSponzorstvo->getNajavaEmisije(), 0, PHP_ROUND_HALF_DOWN);
        $prsegmentFrequency = round(count($blokObican) / $sablonSponzorstvo->getPrsegment(), 0, PHP_ROUND_HALF_DOWN);
        $premiumBlokFrequency = round(count($blokPremium) / $sablonSponzorstvo->getPremiumBlok(), 0, PHP_ROUND_HALF_DOWN);

        foreach ($daniZaKampanju as $dan) {
            $j = 0;
            $k = 1;
            foreach ($blokObican as $value) {
                if ($j % $cobrendingFrequency == 0) {
                    $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $value . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result3 = $dbBroker->selectOneRow($query);
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setBlokZauzeceID($result['BlokZauzeceID']);
                    $blokZauzece->setZauzetoSekundi($result['ZauzetoSekundi'] + 10);
                    $blokZauzece->setPreostaloSekundi($result['PreostaloSekundi'] - 10);
                    $blokZauzece->setNepotvrdjenoSekundi($result['NepotvrdjenoSekundi'] + 10);
                    $condition = " BlokZauzeceID = " . $blokZauzece->getBlokZauzeceID() . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result4 = $dbBroker->update($blokZauzece, $condition);
                    $kampanjaBlok = new KampanjaBlok();
                    $kampanjaBlok->setKampanjaID($kampanjaID);
                    $kampanjaBlok->setRadioStanicaID($sablonSponzorstvo->getRadioStanicaID());
                    $kampanjaBlok->setBlokID($value);
                    $kampanjaBlok->setDatum($dan);
                    $kampanjaBlok->setRedosled(0);

                    $result5 = $dbBroker->insert($kampanjaBlok);
                }
                
                
                if ($k % $najavaOdjavaFrequency == 0) {
                    $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $value . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result6 = $dbBroker->selectOneRow($query);
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setBlokZauzeceID($result['BlokZauzeceID']);
                    $blokZauzece->setZauzetoSekundi($result['ZauzetoSekundi'] + 10);
                    $blokZauzece->setPreostaloSekundi($result['PreostaloSekundi'] - 10);
                    $blokZauzece->setNepotvrdjenoSekundi($result['NepotvrdjenoSekundi'] + 10);
                    $condition = " BlokZauzeceID = " . $blokZauzece->getBlokZauzeceID() . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result7 = $dbBroker->update($blokZauzece, $condition);
                    $kampanjaBlok = new KampanjaBlok();
                    $kampanjaBlok->setKampanjaID($kampanjaID);
                    $kampanjaBlok->setRadioStanicaID($sablonSponzorstvo->getRadioStanicaID());
                    $kampanjaBlok->setBlokID($value);
                    $kampanjaBlok->setDatum($dan);
                    $kampanjaBlok->setRedosled(0);

                    $result8 = $dbBroker->insert($kampanjaBlok);
                }

                if ($j % $prsegmentFrequency == 0) {
                    $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $value . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result9 = $dbBroker->selectOneRow($query);
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setBlokZauzeceID($result['BlokZauzeceID']);
                    $blokZauzece->setZauzetoSekundi($result['ZauzetoSekundi'] + 30);
                    $blokZauzece->setPreostaloSekundi($result['PreostaloSekundi'] - 30);
                    $blokZauzece->setNepotvrdjenoSekundi($result['NepotvrdjenoSekundi'] + 30);
                    $condition = " BlokZauzeceID = " . $blokZauzece->getBlokZauzeceID() . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result10 = $dbBroker->update($blokZauzece, $condition);
                    $kampanjaBlok = new KampanjaBlok();
                    $kampanjaBlok->setKampanjaID($kampanjaID);
                    $kampanjaBlok->setRadioStanicaID($sablonSponzorstvo->getRadioStanicaID());
                    $kampanjaBlok->setBlokID($value);
                    $kampanjaBlok->setDatum($dan);
                    $kampanjaBlok->setRedosled(0);

                    $result11 = $dbBroker->insert($kampanjaBlok);
                }
                $j++;
                $k++;
            }

            $l = 0;
            foreach ($blokPremium as $value) {
                if ($l % $premiumBlokFrequency == 0) {
                    $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $value . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result12 = $dbBroker->selectOneRow($query);
                    $blokZauzece = new BlokZauzece();
                    $blokZauzece->setBlokZauzeceID($result['BlokZauzeceID']);
                    $blokZauzece->setZauzetoSekundi($result['ZauzetoSekundi'] + 30);
                    $blokZauzece->setPreostaloSekundi($result['PreostaloSekundi'] - 30);
                    $blokZauzece->setNepotvrdjenoSekundi($result['NepotvrdjenoSekundi'] + 30);
                    $condition = " BlokZauzeceID = " . $blokZauzece->getBlokZauzeceID() . " AND RadioStanicaID = " . $sablonSponzorstvo->getRadioStanicaID();
                    $result13 = $dbBroker->update($blokZauzece, $condition);
                    $kampanjaBlok = new KampanjaBlok();
                    $kampanjaBlok->setKampanjaID($kampanjaID);
                    $kampanjaBlok->setRadioStanicaID($sablonSponzorstvo->getRadioStanicaID());
                    $kampanjaBlok->setBlokID($value);
                    $kampanjaBlok->setDatum($dan);
                    $kampanjaBlok->setRedosled(0);
                    $result14 = $dbBroker->insert($kampanjaBlok);
                }
                $l++;
            }
        }

        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovano sponzorstvo");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function SablonSponzorstvoUpdate(SablonSponzorstvo $sablonSponzorstvo) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonSponzorstvoID = " . $sablonSponzorstvo->getSablonSponzorstvoID();
        $result = $dbBroker->update($sablonSponzorstvo, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjeno sponzorstvo");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

//Funkcija za brisanje 
    public function SablonSponzorstvoDelete(SablonSponzorstvo $sablonSponzorstvo) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonSponzorstvoID = " . $sablonSponzorstvo->getSablonSponzorstvoID();
        $result = $dbBroker->delete($sablonSponzorstvo, $condition);
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

//Funkcija za punjenje cenovnika
    public function SablonSponzorstvoGetList(FilterSablonSponzorstvo $filter) {
        $query = "select  S.SablonSponzorstvoID,
                 K.Naziv as Klijent,
                 R.Naziv as RadioStanica,
                 RP.Naziv as RadioStanicaProgram,
                 DatumOd,
                 DatumDo,
                 CenaUkupno,
                 Cobrending,
                 Najava,
                 Odjava,
                 Prsegment,
                 PremiumBlok,
                 concat_ws(' ', K1.Ime, K1.Prezime) as KorisnikUneo,
                 DATE_FORMAT(S.VremePostavke,'%d.%m.%Y') as VremePostavke,
                 from sablonsponzorstvo as S
                 inner join klijent K on S.KlijentID = K.KlijentID
                 inner join radiostanica R on S.RadioStanicaID = R.RadioStanicaID
                 inner join radiostanicaprogram RP on S.RadioStanicaProgramID = RP.RadioStanicaProgram
                 inner join korisnik K1 on S.KorisnikID = K1.KorisnikID
                 where ($filter->radioStanicaID is null or (S.RadioStanicaID = $filter->radioStanicaID))";
        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by S.SablonSponzorstvoID asc ";
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
