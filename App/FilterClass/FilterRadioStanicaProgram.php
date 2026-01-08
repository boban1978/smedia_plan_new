<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilterRadioStanicaProgram
 *
 * @author n.lekic
 */
class FilterRadioStanicaProgram {
    var $radioStanicaID = 'null';
    var $naziv = '';
    var $aktivan = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($radioStanicaID, $naziv, $aktivan, $start, $limit, $sort, $dir, $page) {
        $this->radioStanicaID  =($radioStanicaID  != '') ? $radioStanicaID  : 'null';
        $this->naziv =($naziv != '') ? $naziv : '';
        $this->aktivan =($aktivan != '') ? $aktivan : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }

}

?>
