<?php
/**
 * Description of FilterRadioStanicaProgram
 *
 * @author n.lekic
 */
class FilterSablonSponzorstvo {
    var $radioStanicaID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($radioStanicaID, $start, $limit, $sort, $dir, $page) {
        $this->radioStanicaID  =($radioStanicaID  != '') ? $radioStanicaID  : 'null';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }

}

?>
