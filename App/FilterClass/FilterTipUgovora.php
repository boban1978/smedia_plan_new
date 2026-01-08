<?php
/**
 * Description of FilterTipUgovora
 *
 * @author n.lekic
 */
class FilterTipUgovora {
    var $nazivFilter = '';
    var $aktivanFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->nazivFilter =($filterValues->nazivFilter != '') ? $filterValues->nazivFilter : '';
        $this->aktivanFilter =($filterValues->aktivanFilter != '') ? $filterValues->aktivanFilter : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
