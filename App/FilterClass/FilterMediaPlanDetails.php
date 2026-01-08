<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilterMediaPlanDetails
 *
 * @author n.lekic
 */
class FilterMediaPlanDetails {

    var $radioStanicaID = 'null';
    var $kampanjaID = 'null';
    var $blok = '';
    var $datum = '';
    var $korisnikID;
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($radioStanicaID, $kampanjaID, $blok, $datum, $start, $limit, $sort, $dir, $page) {



        $this->radioStanicaID  =($radioStanicaID  != '') ? $radioStanicaID  : 'null';
        //$this->radioStanicaID  = $radioStanicaID;
        //$this->radioStanicaID  = ;
        $this->kampanjaID  =($kampanjaID  != '') ? $kampanjaID  : 'null';
        $this->blok =($blok != '') ? $blok : '';
        $this->datum =($datum != '') ? substr($datum, 0, 10) : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
    
    
    public function setKorisnikID ($korisnikID) {
        $this->korisnikID = $korisnikID;
    }
}

?>
