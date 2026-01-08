<?php
/**
 * Description of FilterPonudaDokument
 *
 * @author n.lekic
 */
class FilterPonudaDokument {
    var $ponudaID = 'null';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($ponudaID, $start, $limit, $sort, $dir, $page) {
        $this->ponudaID = $ponudaID;
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}