<?php
/**
 * Description of FilterBitanDatum
 *
 * @author n.lekic
 */
class FilterBitanDatum {
    var $ID = 'null';
    var $vrsta;
    var $aktivanFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($clientID, $start, $limit, $sort, $dir, $page) {
        $this->ID  = $clientID;
        //$this->vrsta  = $filterValues->vrsta;
        $this->vrsta  = 2; // Ovde je zakucano dok se bitan datum vezuje smao za klijenta
        //$this->aktivanFilter =($filterValues->aktivanFilter != '') ? $filterValues->aktivanFilter : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>




