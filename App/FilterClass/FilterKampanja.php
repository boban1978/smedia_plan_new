<?php
/**
 * Description of FilterKampanja
 *
 * @author n.lekic
 */
class FilterKampanja {
    var $kampanjaNazivFilter = '';
    var $datumPocetkaFilter = '';
    var $datumKrajaFilter = '';
    var $statusFilterID = 'null';
    var $nacinPlacanjaFilterID = 'null';
    var $klijentListFilter = '';
    var $agencijaListFilter = '';
    var $tipKorisnika = 'null';
    var $korisnikID;
    var $klijentID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->kampanjaNazivFilter =($filterValues->kampanjaNazivFilter != '') ? $filterValues->kampanjaNazivFilter : '';
        $this->datumPocetkaFilter =($filterValues->datumPocetkaFilter != '') ? $filterValues->datumPocetkaFilter : '';
        $this->datumKrajaFilter =($filterValues->datumKrajaFilter != '') ? $filterValues->datumKrajaFilter : '';
        $this->statusFilterID  =($filterValues->statusFilterID  != '') ? $filterValues->statusFilterID  : 'null';
        $this->nacinPlacanjaFilterID  =($filterValues->nacinPlacanjaFilterID  != '') ? $filterValues->nacinPlacanjaFilterID  : 'null';
        $klijentList = "";
        $agencijaList = "";
        for ($i = 0; $i < count($filterValues->klijentListFilter); $i++){
            $klijentList .= $filterValues->klijentListFilter[$i].",";
        }
        for ($j = 0; $j < count($filterValues->agencijaListFilter); $j++){
            $agencijaList .= $filterValues->agencijaListFilter[$j].",";
        }
        $this->klijentListFilter =(count($filterValues->klijentListFilter) > 0) ? substr($klijentList, 0, strlen($klijentList)-1) : '';
        $this->agencijaListFilter =(count($filterValues->agencijaListFilter) > 0) ? substr($agencijaList, 0, strlen($agencijaList)-1) : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
    
    public function setTipKorisnik ($tipKorisnik) {
        $this->tipKorisnika = $tipKorisnik;
    }
    
    public function setKorisnikID ($korisnikID) {
        $this->korisnikID = $korisnikID;
    }
    
    public function setKlijentID ($klijentID) {
        if ($klijentID != '') {
            $this->klijentID = $klijentID;
        } else {
            $this->klijentID = 'null';
        }
    }
}

?>
