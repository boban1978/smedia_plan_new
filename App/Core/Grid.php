<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Grid
 *
 * @author n.lekic
 */
class Grid {
    var $rs;
    var $rowStart;
    var $limit;
    var $columns;
    var $items;
    var $total;
    var $sucess;

    public function  __construct($rs, $rowStart, $limit, $columns) {
        $this->rs = $rs;
        $this->rowStart = $rowStart;
        $this->limit = $limit;
        $this->columns = $columns;
        $this->items = array();
    }

    public function SetRowStart ($rowStart) {
        $this->rowStart = $rowStart;
    }

    public function SetLimit ($limit) {
        $this->limit = $limit;
    }

    public function  SetTotal($total) {
        $this->total = $total;
    }

    public function AddItems () {
        $flag = true;
        for ($i=0; ((($this->rowStart+$i) < ($this->rowStart+$this->limit)) && (($this->rowStart+$i) < $this->total)) ; $i++) {
            //$flag1 = $this->db->FetchSeekResultRow($this->rs, ($this->rowStart+$i), $values);
            $values = sqlsrv_fetch_array($this->rs, SQLSRV_FETCH_NUMERIC, SQLSRV_SCROLL_ABSOLUTE, ($this->rowStart+$i));
                for ($j=0; $j<count($this->columns); $j++) {
                $this->items[$i][$this->columns[$j]]=$values[$j];
            }
            $values = ($values != 0) ? 1 : 0;
            $flag &=$values;
        }
        $this->sucess = $flag;   
    }

    public function GetTotal() {
        return $this->total;
    }

    public function GetItems() {
       return $this->items;
    }
    //Napomena columns mora sadrzati samo jednu kolonu, najcesce EntryID
    public function GetItemsForCheckbox($listName) {
        $response = "{".$listName.":[";
        for ($i = 0; $i < $this->total; $i++) {
            $response .= $this->items[$i][$this->columns[0]];
            if ($i < $this->total - 1) {
                $response .= ",";
            }
        }
        $response .= "]}";
        return $response;
    }


    public function GetSucess() {
       return $this->sucess;
    }
}
?>
