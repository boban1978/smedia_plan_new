<?php
/**
 * Description of FilterKlijent
 *
 * @author n.lekic
 */
class FilterKlijent {
    var $naziv = '';
    var $adresa = '';
    var $drzava = '';
    var $pib = '';
    var $maticniBroj = '';
    var $kontaktIme = '';
    var $kontaktEmail = '';
    var $tipUgovoraID = 'null';
    var $delatnostID = 'null';
    var $agencijaListFilter = '';
    var $page;
    var $start;
    var $limit;
    var $sort;
    var $dir;
    
    public function __construct($filterValues, $start, $limit, $sort, $dir, $page) {
        $this->naziv =($filterValues->naziv != '') ? $filterValues->naziv : '';
        $this->adresa =($filterValues->adresa != '') ? $filterValues->adresa : '';
        $this->drzava =($filterValues->drzava != '') ? $filterValues->drzava : '';
        $this->pib =($filterValues->pib != '') ? $filterValues->pib : '';
        $this->maticniBroj =($filterValues->maticniBroj != '') ? $filterValues->maticniBroj : '';
        $this->kontaktIme =($filterValues->kontaktIme != '') ? $filterValues->kontaktIme : '';
        $this->kontaktEmail =($filterValues->kontaktEmail != '') ? $filterValues->kontaktEmail : '';
        $this->tipUgovoraID  =($filterValues->tipUgovoraID  != '') ? $filterValues->tipUgovoraID  : 'null';
        $this->delatnostID  =($filterValues->delatnostID  != '') ? $filterValues->delatnostID  : 'null';
        $agencijaList = "";
        for ($j = 0; $j < count($filterValues->Agencije); $j++){
            $agencijaList .= $filterValues->Agencije[$j].",";
        }
        $this->agencijaListFilter =(count($filterValues->Agencije) > 0) ? substr($agencijaList, 0, strlen($agencijaList)-1) : '';
        $this->page = $page;
        $this->start = $start;
        $this->limit = $limit;
        $this->sort = $sort;
        $this->dir = $dir;
    }
}

?>
