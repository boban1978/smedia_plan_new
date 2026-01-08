<?php
/**
 * Description of FilterRola
 *
 * @author n.lekic
 */
class FilterRola {
    var $rolaNazivFilter = '';
    var $rolaPrivilegijaFilterID = 'null';
    var $aktivanFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->rolaNazivFilter =($filterValues->rolaNazivFilter != '') ? $filterValues->rolaNazivFilter : '';
        $this->aktivanFilter =($filterValues->aktivanFilter != '') ? $filterValues->aktivanFilter : '';
        $this->rolaPrivilegijaFilterID  =($filterValues->rolaPrivilegijaFilterID  != '') ? $filterValues->rolaPrivilegijaFilterID  : 'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
