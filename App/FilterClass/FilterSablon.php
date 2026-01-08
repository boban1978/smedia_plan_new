<?php
/**
 * Description of FilterSablon
 *
 * @author n.lekic
 */
class FilterSablon {
    var $naziv = '';
    var $aktivan = '';
    var $radioStanicaID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->naziv =($filterValues->naziv != '') ? $filterValues->naziv : '';
        $this->aktivan =($filterValues->aktivan != '') ? $filterValues->aktivan : '';
        $this->radioStanicaID  =($filterValues->radioStanicaID  != '') ? $filterValues->radioStanicaID  : 'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }

}

?>
