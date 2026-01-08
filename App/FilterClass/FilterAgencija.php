<?php
/**
 * Description of FilterAgencija
 *
 * @author n.lekic
 */
class FilterAgencija {

    var $agencijaID = 'null';

    var $naziv = '';
    var $adresa = '';
    var $drzava = '';
    var $kontakt = '';
    var $email = '';
    var $klijentID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->agencijaID  =($filterValues->agencijaID!='') ? $filterValues->agencijaID  : 'null';
        $this->naziv =($filterValues->naziv != '') ? $filterValues->naziv : '';
        $this->adresa =($filterValues->adresa != '') ? $filterValues->adresa : '';
        $this->drzava  =($filterValues->drzava  != '') ? $filterValues->drzava  : '';
        $this->kontakt  =($filterValues->kontakt  != '') ? $filterValues->kontakt  : '';
        $this->email  =($filterValues->email  != '') ? $filterValues->email  : '';
        $this->klijentID  =($filterValues->klijentID  != '') ? $filterValues->klijentID  : 'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
