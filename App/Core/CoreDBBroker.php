<?php
//include_once '../Core/CoreEngine.php';
class CoreDBBroker {
	private $connection;
	
	public function __construct() {
		$this->connection = CoreEngine::getConnection();
		$this->connection->connect();
	}
	
	public function close() {
		$this->connection->close();
	}
        
        public function beginTransaction() {
		$this->connection->setAutoCommit(false);
	}
        
        public function rollback() {
		$this->connection->rollback();
	}
	
	public function commit() {
		$this->connection->commit();
	}
	
	public function insert($object) {
		$attributes = '';
		$values = '';
		$allAttributes = array();
		
		$allAttributes = $object->getAllAttributes();
		if(empty($allAttributes)) {
			throw new Exception('Object is not set.');
		}
                
                $i = 0;
                foreach ($allAttributes as $key => $val) {
                        if($i == 0) {
                            if($val == -1) {
                                $i = 1;
                                continue;
                            }
                        }
                        
                        if($val === null) {
                                $attributes .= $key.',';
                                $values .= 'NULL,';
                        }
                        
                        if($val === 'true') {
                                $attributes .= $key.',';
                                $values .= 'true,';
                        }
                        
                        if($val === 'false') {
                                $attributes .= $key.',';
                                $values .= 'false,';
                        }

                        if($val != '') {
                            if ($val === 'true' || $val === 'false') {
                                continue;    
                            } else {
                                $attributes .= $key.',';
                                $values .= '\''.mysql_real_escape_string($val).'\',';
                            }
                        }
                }
                
                    
		$attributes = substr($attributes, 0, strlen($attributes)-1);
		$values = substr($values, 0, strlen($values)-1);


		$query = 'INSERT INTO '.$object->getTableName(). ' ('.$attributes.') VALUES ('.$values.')';





		$result = $this->connection->query($query);

/*
        if($object->getTableName()=="faktura"){
            die($query." --- ".$result);
        }*/




                return $result;
	}


        /*
	public function update($object, $set, $condition) {
		$query = 'UPDATE '.$object->getTableName(). ' SET '.$set. ' WHERE '.$condition;
		$result = $this->connection->query($query);
                return $result;
	}
        */
	
        public function update($object, $condition) {
                $allAttributes = array();
		$allAttributes = $object->getAllAttributes();
                
                $set = '';
                
                foreach ($allAttributes as $key => $value) {
                        if($value === null) {
                                $set .= $key.'=NULL,';
                        }
                        
                        if($value === 'true') {
                                $set .= $key.'=true,';
                        }
                        
                        if($value === 'false') {
                                $set .= $key.'=false,';
                        }

                        if($value != '') {
                            if ($value === 'true' || $value === 'false') {
                                continue;    
                            } else {
                                $set .= $key.'=\''.mysql_real_escape_string($value).'\',';
                            }
                        }  
                }
                $set = substr($set, 0, -1);
                $query = 'UPDATE '.$object->getTableName(). ' SET '.$set. ' WHERE '.$condition;


            /*send_respons_boban($query);
            exit;*/


		$result = $this->connection->query($query);
                return $result;
        }
        
	public function delete($object, $condition) {
		$query = 'DELETE FROM '.$object->getTableName(). ' WHERE '.$condition;

        /*
        send_respons_boban($query);
        exit;*/

		$result = $this->connection->query($query);
                return $result;
	}
	
	public function selectManyRows($query, $start = "null", $limit = "null") {
		//Ovde prvo pripremamo query za NumRows
                $resultSetForNumRows =$this->connection->query($query);
                $numRows = $this->connection->numRows($resultSetForNumRows);
                //Ovde pripremamo query za same podatke
                if ($start !== "null" && $limit !== "null" ) {
                    $queryWithLimit = $query." LIMIT $start, $limit";
                }
                //if (!isset($start) && !isset($limit)) {
                if ($start == "null" && $limit == "null") {
                    $resultSet =$this->connection->query($query);
                } else {
                    $resultSet =$this->connection->query($queryWithLimit);
                }              
                $result = true;
                if ($resultSet == false) {
                    $result = false;
                }
                
                if ($result != false)
                {
                    unset($result);
                    $rows = $this->connection->getAssocRows($resultSet);
                    $result['rows'] =$rows;
                    $result['numRows'] = $numRows;
                }
                return $result;
	}
        
        
        public function selectOneRow($query) {
		//$result = $this->connection->query($query);
                $resultSet =$this->connection->query($query);
                $result = true;
                if ($resultSet === false) {
                    $result = false;
                }

                if ($result !== false)
                {
                    unset($result);
                    $rows = $this->connection->getAssocRow($resultSet);
                }
                return $rows;
	}
        
    public function getDataForComboBox($object){
        $select = '';
        $columns = array();

        $columns = $object->getColumnForComboBox();
        if(empty($columns)) {
            throw new Exception('Object is not set.');
        }

        foreach ($columns as $key => $val) {
                        if($val != '') {
                $select .= $val.' as '.$key.',';
            }
        }
        $select = substr($select, 0, strlen($select)-1);

/*
        if($uslov!=""){
            $uslov=" AND ".$uslov;
        }*/




        $query = 'SELECT '.$select. ' FROM '.$object->getTableName().' WHERE AKTIVAN = 1';
        $result = $this->selectManyRows($query);
        return $result;
    }



    public function getDataForComboBox_my($object)
    {//zbog sorta po id
        $select = '';
        $columns = array();

        $columns = $object->getColumnForComboBox();
        if (empty($columns)) {
            throw new Exception('Object is not set.');
        }

        $field_id = '';
        foreach ($columns as $key => $val) {
            if ($val != '') {
                $select .= $val . ' as ' . $key . ',';
                if ($field_id == '') {
                    $field_id = $val;
                }
            }
        }
        $select = substr($select, 0, strlen($select) - 1);

        if ($field_id != '') {
            $query = 'SELECT ' . $select . ' FROM ' . $object->getTableName() . ' WHERE AKTIVAN = 1 order by '.$field_id;
        }else{
            $query = 'SELECT ' . $select . ' FROM ' . $object->getTableName() . ' WHERE AKTIVAN = 1';
        }

        $result = $this->selectManyRows($query);
        return $result;
    }


        
        public function getLastInsertedId() {
            return $this->connection->getLastId();
        }
        
        public function simpleQuery($query) {
            $result = $this->connection->query($query);
            return $result;
        }	
}