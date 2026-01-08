<?php
/**
 * Description of FilterKorisnik
 *
 * @author n.lekic
 */
class FilterKorisnik {
    var $korisnickoImeFilter = '';
    var $aktivanFilter = '';
    var $imePrezimeFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->korisnickoImeFilter =($filterValues->korisnickoImeFilter != '') ? $filterValues->korisnickoImeFilter : '';
        $this->aktivanFilter =($filterValues->aktivanFilter != '') ? $filterValues->aktivanFilter : '';
        $this->imePrezimeFilter =($filterValues->imePrezimeFilter != '') ? $filterValues->imePrezimeFilter : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
