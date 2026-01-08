<?php

class FilterGlas {
    var $imePrezimeFilter = '';
    var $aktivanFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->imePrezimeFilter =($filterValues->imePrezimeFilter != '') ? $filterValues->imePrezimeFilter : '';
        $this->aktivanFilter =($filterValues->aktivanFilter != '') ? $filterValues->aktivanFilter : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
