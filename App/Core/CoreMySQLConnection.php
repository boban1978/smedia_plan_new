<?php
//include_once '../../Config/define.php';
class CoreMySQLConnection {
	private $link;
	private $open;
	private $charset;
	
	public function __construct() {
		$this->open = false;
		$this->setCharset('utf8');
	}
	
	public function setAutoCommit($bool) {
		if(!$bool)
		mysql_query('BEGIN', $this->link);
	}
	
	public function setCharset($charset){
		$this->charset = $charset;
	}
	
	public function close() {
		mysql_close($this->link);
		$this->open = false;
	}
	
	public function rollback() {
		mysql_query('ROLLBACK', $this->link);
	}
	
	public function commit() {
		mysql_query('COMMIT', $this->link);
	}
	
	public function isOpen() {
		return $this->open;
	}
        
        public function getLastId() {
            return mysql_insert_id();
        }

        public function numRows($resultSet) {
            $result = mysql_num_rows($resultSet);
            if ($result === false) {
                //throw new Exception('Invalid query: '. mysql_error());
                if(!isset($_SESSION['mysqlGreska'])) {
                    $_SESSION['mysqlGreska'] = mysql_errno();
                }
            }
            
            return $result;    
        }
        
        public function getAssocRows($resultSet){
            $result = array();
            while($row = mysql_fetch_assoc($resultSet))
            {
                foreach ($row as $key => $value) {
                    $result_row[$key] = $value;
                }
                $result[] = $result_row;
            }
            mysql_free_result($resultSet);
            return $result;
        }
        
       public function getAssocRow($resultSet){
            $row = mysql_fetch_assoc($resultSet);
            mysql_free_result($resultSet);
            return $row;
        }
        /*
	public function query($query) {
		$result = mysql_query($query, $this->link);
		if ($result) {
			return $result;
		} else {
			throw new Exception('Invalid query: '. mysql_error());
		}
	}
        */
        public function query($query) {
		$result = mysql_query($query, $this->link);
		if (!$result) {
                    if(!isset($_SESSION['mysqlGreska'])) {
			$_SESSION['mysqlGreska'] = $query." --- ".mysql_errno($this->link);






                    }    
		}
                
                return $result;
	}

	
	public function connect() {
		$link = mysql_connect(HOSTNAME, USERNAME, PASSWORD);
		if (!$link) {
			//throw new Exception('Unable to connet to database.');
                    if(!isset($_SESSION['mysqlGreska'])) {
                        $_SESSION['mysqlGreska'] = mysql_errno();
                    }
		}
		$db_selected = mysql_select_db(DBNAME, $link) ;
		if (!$db_selected) {
			//throw new Exception('Unable to found database.');
                    if(!isset($_SESSION['mysqlGreska'])) {
                        $_SESSION['mysqlGreska'] = mysql_errno();
                    }
		}
		$this->link = $link;
		$this->open = true;
		mysql_set_charset($this->charset, $this->link);
	}
}