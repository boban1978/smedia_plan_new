<?php
/**
 * Description of FiletrDelatnost
 *
 * @author n.lekic
 */
class FilterCenovnikUsluga {
    var $nazivFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->nazivFilter =($filterValues->nazivFilter != '') ? $filterValues->nazivFilter : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
