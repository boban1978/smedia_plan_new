<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AjaxRequestInfo
 *
 * @author n.lekic
 */
class CoreAjaxResponseInfo
    {
        public  $success;
        public  $data;
        public  $total;
        public  $msg;
        public  $errorNumber; 
        
        public function __construct() {
        }
        
        public function __destruct() {
        }


        public function SetSuccess($value) {
            $this->success = $value;
        }
        public function SetData($value) {
            $this->data = $value;
        }
        
        public function SetTotal($value) {
            $this->total = $value;
        }
        
        public function SetErrorNumber($errors) {
            for ($i = 0; $i < count($errors); $i++) {
                switch ($errors[$i]['code']) {
                    case 547:
                        $this->errorNumber = $errors[$i]['code'];
                        $this->msg = "Ovaj podatak se koristi u zavisnim tabelama. Ne možete ga brisati!";
                        break;
                    case 2627:
                        $this->errorNumber = $errors[$i]['code'];
                        $this->msg = "Ova vrednost već postoji!";
                        break;
                    case 2601:
                        $this->errorNumber = $errors[$i]['code'];
                        $this->msg = "Ova vrednost već postoji!";
                        break;
                }
            }
            
        }
        
        public function SetMessage($value) {
            $this->msg = '\''.$value.'\'';
        }
        
        public function GetErrorNumber () {
            return $this->errorNumber;
        }
        public function GetResponse() {
            $response = '{';
            if($this->success == 'true')
                $response .= 'success:true ';
            else
                $response .= 'success:false ';
            
            if(isset ($this->data)) {
                $response .= ', '.$this->data;
            }
            
            if(isset ($this->total)) {
                $response .= ', '.$this->total;
            }
            
            if (isset ($this->msg)) {
                $response .= ', msg:'.$this->msg;
            }
            
            $response .= '}';
            
            return $response;
            
        }
    }
?>
