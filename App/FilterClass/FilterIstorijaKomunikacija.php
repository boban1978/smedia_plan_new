<?php
class FilterIstorijaKomunikacija {
    var $klijentID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($klijentID, $start, $limit, $sort, $dir, $page) {
        $this->klijentID = $klijentID;
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
