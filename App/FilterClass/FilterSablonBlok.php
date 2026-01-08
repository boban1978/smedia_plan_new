<?php
/**
 * Description of FilterSablonBlok
 *
 * @author n.lekic
 */
class FilterSablonBlok {
    var $sablonBlokID = 'null';
    var $aktivan = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->sablonBlokID  =($filterValues->sablonBlokID  != '') ? $filterValues->sablonBlokID  :  'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
