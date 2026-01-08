<?php

/**
 * Description of Sablon
 *
 * @author n.lekic
 */
class SablonClass {

    public function SablonGetForComboBox(Sablon $sablon) {
        $radioStanicaID = $sablon->getRadioStanicaID();
        $query = "select 
                    s.SablonID as EntryID,
                    s.Naziv as EntryName
                    from sablon s 
            where Aktivan = 1";
        if (!empty($radioStanicaID)) {
           $query .= " AND RadioStanicaID = ".$sablon->getRadioStanicaID();
        }
        $dbBroker = new CoreDBBroker();
        $result = $dbBroker->selectManyRows($query);
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

    public function SablonLoad(Sablon $sablon) {
        $dbBroker = new CoreDBBroker();
        $query = "SELECT sablonblok.*, 0 as SpotUkupno, 0 as CenaUkupno, sablon.Naziv, blok.VremeStart, blok.VremeEnd, blok.Trajanje
                    FROM sablonblok
                    INNER JOIN sablon
                    ON sablonblok.SablonID = sablon.SablonID
                    INNER JOIN blok 
                    ON sablonblok.BlokID = blok.BlokID
                    WHERE sablonblok.SablonID = " . $sablon->getSablonID() . "
                    ORDER BY sablonblok.Datum, sablonblok.BlokID";
        $result = $dbBroker->selectManyRows($query);
        $cenaKampanje = 0;
        $dani = array();
        foreach ($result['rows'] as $row) {
            $cenaKampanje = $row['CenaUkupno'];
            $dani[$row['Datum']][] = array('Title' => $row['Naziv'], 'StartDate' => '2012-01-01 ' . $row['VremeStart'], 'EndDate' => '2012-01-01 ' . $row['VremeEnd'], 'Duration' => $row['SpotUkupno'], 'CommercialBlockOrderID' => $row['Redosled']);
        }
        //echo $query;
        // "schedulerDates" : [{"Id":1,"Name":"2012-05-22"},{"Id":2,"Name":"2012-05-23"}]
        // "schedulerCommercial" : [{"Id":1,"Title":"Reklama","StartDate":"2012-01-01 12:45:00","EndDate":"2012-01-01 12:47:30","ResourceId":1,"Duration":"150","OtherClient":false}
        $i = 1;
        $j = 1;
        $schedulerDates = '"schedulerDates":[';
        $schedulerCommercial = '"schedulerCommercial":[';
        foreach ($dani as $dan => $vrednosti) {
            $schedulerDates .= '{"Id":' . $i . ',"Name":"' . $dan . '"},';
            foreach ($vrednosti as $vrednost) {
                $schedulerCommercial .= '{"Id":' . $j . ',"Title":"' . $vrednost['Title'] . '","StartDate":"' . $vrednost['StartDate'] . '","EndDate":"' . $vrednost['EndDate'] . '","ResourceId":' . $i . ',"Duration":"' . $vrednost['Duration'] . '","OtherClient":false, "CommercialBlockOrderID":"' . $vrednost['CommercialBlockOrderID'] . '"},';
                $j++;
            }
            $i++;
        }
        $schedulerDates = substr($schedulerDates, 0, strlen($schedulerDates) - 1);
        $schedulerCommercial = substr($schedulerCommercial, 0, strlen($schedulerCommercial) - 1);
        $schedulerDates .= ']';
        $schedulerCommercial .= ']';
        $capmaignePrice = '"capmaignePrice":' . $cenaKampanje;
        $data = 'data:{' . $capmaignePrice . ',' . $schedulerDates . ',' . $schedulerCommercial . '}';
        $responseNew = new CoreAjaxResponseInfo();
        if ($result || $result === 0) {
            $responseNew->SetSuccess('true');
            $responseNew->SetData($data);
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

    public function SablonLoadDetails(Sablon $sablon) {

        $query = "select 
                    S.SablonID as sablonID,
                    S.RadioStanicaID as radioStanicaID,
                    S.Naziv as naziv,
                    S.Trajanje as trajanje,
                    S.Popust as popust,
                    S.KorisnikID as korisnikID,
                    S.VremePostavka as vremePostavka,
                    S.DaniZaEmitovanje as daniZaEmitovanje,
                    S.Ucestalost as ucestalost,
                    S.Aktivan as aktivan
                    from sablon as S
                    where S.SablonID = " . $sablon->getSablonID();




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

    public function SablonInsert(Sablon $sablon) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($sablon);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage('Uspešno insertovan šablon');
            $dbBroker->close();
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
        }
        return $responseNew;
    }

    public function SablonInsertNedeljni(Sablon $sablon) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $result = $dbBroker->insert($sablon);
        $sablonLastID = $dbBroker->getLastInsertedId();
        if (isset($_SESSION['sessSablonID'])) {
            unset($_SESSION['sessSablonID']);
        }

        $_SESSION['sessSablonID'] = $sablonLastID;
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $dbBroker->close();
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
            $dbBroker->close();
            return $responseNew; //Ovde treba videti sta treba da se vrati ukoliko se zahtev ne snimi kako treba
        }
        $object = new TmpKampanjaClass(1, $sablon->getNaziv(), $sablon->getDatumPocetak(), $sablon->getDatumZavrsetak(), 0, 0, 5, //Ovaj parametar govori o sekundama koliko traje spot i mora se definisati u paremtrima negde
                array(1, 2, 3), $sablon->getDani(), 1, // Ovo s emora yakucati da bi oba tipa bloka mogli da uymemo u obzir
                NULL, $sablon->getNedeljniSablon()); // Ovo gori da je to nedeljni sablon kako bi se dodavanje blokova poyvalo vise puta 

        $tmp = $object->getTmpKampanja();

        if (isset($_SESSION['tmpKampanja'])) {
            unset($_SESSION['tmpKampanja']);
        }

        $_SESSION['tmpKampanja'] = serialize($object);

        return $tmp;
    }

    public function SacuvajZauzeteBlokove($tmpKampanja, $dbBroker) {
        $error = 0;
        $izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
        $sablonLastId = $_SESSION['sessSablonID'];
        if (isset($sablonLastId) && $sablonLastId > 0) {
            foreach ($izabraniBlokovi as $dan => $niz) {
                foreach ($niz as $izabraniBlok => $cena) {
                    $sablonBlok = new SablonBlok();
                    $blokPozicija = array();
                    $blokPozicija = explode('/', $izabraniBlok);

                    $sablonBlok->setSablonID($sablonLastId);
                    $sablonBlok->setBlokID($blokPozicija[0]);
                    $sablonBlok->setDatum($dan);
                    $sablonBlok->setRedosled($blokPozicija[1]);

                    $result = $dbBroker->insert($sablonBlok);

                    if (!$result) {
                        $error = 1;
                    }

                    unset($sablonBlok);
                }
            }
        } else {
            $error = 1;
        }

        if ($error == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function ZauzmiBlokove($tmpKampanja, $dbBroker) {
        $error = 0;
        $izabraniBlokovi = $tmpKampanja->getIzabraniBlokovi();
        foreach ($izabraniBlokovi as $dan => $niz) {
            foreach ($niz as $blok => $cena) {
                $blokPozicija = array();
                $blokPozicija = explode('/', $blok);
                $query = "SELECT * FROM blokzauzece WHERE Datum = '" . $dan . "' AND BlokID = " . $blokPozicija[0];
                $result = $dbBroker->selectOneRow($query);

                if ($blokPozicija[1] == 1 && $result['ZauzetaPrva'] == 1) {
                    $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi zeleli, molimo Vas da ponovo kreirate sablon.';
                    $error = 1;
                } elseif ($blokPozicija[1] == 2 && $result['ZauzetaDruga'] == 1) {
                    $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi zeleli, molimo Vas da ponovo kreirate sablon.';
                    $error = 1;
                } elseif ($result['PreostaloSekundi'] < $tmpKampanja->getTrajanjeSpota()) {
                    $_SESSION['nekaGreska'] = 'Neko je u medjuvremenu zauzeo blok koji ste Vi zeleli, molimo Vas da ponovo kreirate sablon.';
                    $error = 1;
                } else {
                    if ($result === false) {
                        $error = 1;
                    }
                }
            }
        }
        if ($error == 1) {
            return false;
        } else {
            return true;
        }
    }

    public function SablonPotvrdi($tmpKampanja) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $responseNew = new CoreAjaxResponseInfo();

        $result = $this->ZauzmiBlokove($tmpKampanja, $dbBroker);
        $result2 = $this->SacuvajZauzeteBlokove($tmpKampanja, $dbBroker);

        if ($result && $result2) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno insertovana kampanja");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    public function SablonUpdate(Sablon $sablon) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonID = " . $sablon->getSablonID();
        $result = $dbBroker->update($sablon, $condition);
        $responseNew = new CoreAjaxResponseInfo();
        if ($result) {
            $dbBroker->commit();
            $responseNew->SetSuccess('true');
            $responseNew->SetMessage("Uspešno izmenjen šablon");
        } else {
            $dbBroker->rollback();
            $responseNew->SetSuccess('false');
            $responseNew->SetMessage(CoreError::getError());
        }
        $dbBroker->close();
        return $responseNew;
    }

    //Funkcija za brisanje 
    public function SablonDelete(Sablon $sablon) {
        $dbBroker = new CoreDBBroker();
        $dbBroker->beginTransaction();
        $condition = " SablonID = " . $sablon->getSablonID();
        $result = $dbBroker->delete($sablon, $condition);
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

    //Funkcija za punjenje grida sa sablonima
    public function SablonGetList(FilterSablon $filter) {
        $query = "select
                    S.SablonID as sablonID,
                    R.Naziv as radioStanica,
                    S.Naziv as naziv,
                    S.Trajanje as trajanje,
                    S.Popust as popust,
                    concat_ws(' ', K.Ime, K.Prezime) as uneo,
                    DATE_FORMAT(S.VremePostavka,'%d.%m.%Y') as vremePostavka,
                    case 
                        when S.Aktivan = 1 then 'Aktivan'
                        when S.Aktivan = 0 then 'Neaktivan'
                    end as aktivan
                    from sablon as S
                    inner join korisnik as K on S.KorisnikID = K.KorisnikID 
                    inner join radiostanica as R on S.RadioStanicaID = R.RadioStanicaID
                    where ('$filter->naziv' = '' or (S.Naziv like '%$filter->naziv%'))
                    and ('$filter->aktivan' = '' or (S.Aktivan = '$filter->aktivan'))
                    and ('$filter->radioStanicaID' = 'null' or (S.RadioStanicaID = '$filter->radioStanicaID'))";


        if ($filter->sort != '') {
            $querySort = " order by $filter->sort $filter->dir";
        } else {
            $querySort = " order by S.Naziv asc ";
        }
        $query .= $querySort;

/*
        var_dump($filter);
        exit;*/



        //file_put_contents("neki.txt", $query);
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
