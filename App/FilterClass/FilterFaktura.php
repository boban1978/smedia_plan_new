<?php
/**
 * Description of FilterBlok
 *
 * @author n.lekic
 */
class FilterFaktura {
    var $kampanjaID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($kampanjaID, $start, $limit, $sort, $dir, $page) {
        $this->kampanjaID  =($kampanjaID  != '') ? $kampanjaID  : 'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
