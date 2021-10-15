<?php
namespace System\Driver;
use PDO;
class PDODriver extends Database{
    private static $db = null;
    private $table;
    
    public function __construct(&$dbsetting){
        parent::__construct($dbsetting);
    }

    static function getConnection(){
        if(self::$db === null){
        	try{
	            self::$db = new PDO('mysql:host='.self::$host.';dbname='.self::$dbname.';charset='.self::$charset, self::$username, self::$password,  array(PDO::ATTR_PERSISTENT => true));
	            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        	}catch(PDOException $e){
        		die($e->getMessage());
        	}
        }
        return self::$db;
    }

    static function close(&$db){
        $db = null;
    }
    
    public function insert_multiple($tablename, $columsname, $value) {
		$colums = '';
		$values = '';
		$values_unit = '';
		$ic = 0;
		$iv = 0;
		$result = null;
		$db = null;
		
		$value_insert = array();
		
        if(count($columsname) != count($value[0])) {
            return false;
        } else {
		    foreach($columsname as $index => $val) {
		        if($colums === '') {
		            $colums = '`'.$val.'`';
		        } else {
		            $colums .= ',`'.$val.'`';
		        }
		    }
		    
		    foreach($value[0] as $index => $val) {
                if($values_unit === '') {
                    $values_unit = '?';
                } else {
                    $values_unit .= ',?';
                }
		    }
		    
		    foreach($value as $index => $val) {
		        if($values === '') {
		            $values = '('.$values_unit.')';
		        } else {
		            $values .= ',('.$values_unit.')';
		        }
		        $value_insert = array_merge($value_insert, $val);
		    }
            
			try{
				$db 	  = self::getConnection();
				$query  = 'INSERT INTO '.$tablename.'('.$colums.') VALUES '.$values;
				$exe 	  = $db->prepare($query);
				$exe->execute($value_insert);
				$result = $exe->rowCount();
				self::close($db);
				unset($db);
				return $result;
			}catch(PDOException $e){
				$e->getMessage();
				self::close($db);
				unset($db);
				return $result;
			}
        }
    }
    
	//execute insert data to table
	public function insert($tablename,$columsname,$value, $type = 1){
		$colums = '';
		$values = '';
		$ic = 0;
		$iv = 0;
		$result = null;
		$db = null;
		
		if(count($columsname) != count($value)){
			return false;
		}
		else{
		    foreach($columsname as $index => $val) {
		        if($colums === '') {
		            $colums = '`'.$val.'`';
		        } else {
		            $colums .= ',`'.$val.'`';
		        }
		    }
		    
            foreach($value as $index => $val) {
                if($values === '') {
                    $values = '?';
                } else {
                    $values .= ',?';
                }
            }
            
			try{
				$db 	  = self::getConnection();
				$query  = 'INSERT INTO '.$tablename.'('.$colums.') VALUES('.$values.')';
				$exe 	  = $db->prepare($query);
				$exe->execute($value);
				
				if($type == 1) {
				    $result = $db->lastInsertId();
				} else {
				    $result = $exe->rowCount();
				}
				self::close($db);
				unset($db);
				return $result;
			}catch(PDOException $e){
				$e->getMessage();
				self::close($db);
				unset($db);
				return $result;
			}
		}
	}

	// execute select table
	public function select($tablename,$where,$value){
		$result = null;
		$db = null;
		try{
			$db 	  = self::getConnection();
			
			if($value){
				$query  = 'SELECT * FROM '.$tablename.' WHERE '.$where;
			}else{
				$query  = 'SELECT * FROM '.$tablename.' '.$where;
			}
			
			$exe 	  = $db->prepare($query);
			$exe->execute($value);
			$result = $exe->fetchAll(PDO::FETCH_ASSOC);
			$exe->closeCursor();
			self::close($db);
			unset($db);
			return $result;
		}catch(PDOException $e){
			$e->getMessage();
			self::close($db);
			unset($db);
			return $result;
		}
	}

	// execute delete table
	public function delete($table,$where,$value){
		$result = null;
		$db = null;
		try {
			$db 	  = self::getConnection();
			$query    = 'DELETE FROM '.$table.' WHERE '.$where;
			$exe 	  = $db->prepare($query);
			$exe->execute($value);
			$result   = $exe->rowCount();
			self::close($db);
			unset($db);
			return $result;
		} catch (PDOException $e) {
			$e->getMessage();
			self::close($db);
			unset($db);
			return $result;
		}
	}

	//execute select table with specific colums
	public function selectColumns($colums,$table,$where,$value){
		$colum  = '';
		$ic = 0;
		$result = null;
		$db = null;
	    foreach($colums as $index => $val) {
	        if($colum === '') {
	            $colum = '`'.$val.'`';
	        } else {
	            $colum .= ',`'.$val.'`';
	        }
	    }
	    
		try{
			$db 	  = self::getConnection();
			
			if($value){
				$query  = 'SELECT '.$colum.' FROM '.$table.' WHERE '.$where.'';
			}else{
				$query  = 'SELECT '.$colum.' FROM '.$table.' '.$where.'';
			}
			$exe 	  = $db->prepare($query);
			$exe->execute($value);
			$result = $exe->fetchAll(PDO::FETCH_ASSOC);
			$exe->closeCursor();
			self::close($db);
			unset($db);
			return $result;
		}catch(PDOException $e){
			$e->getMessage();
			self::close($db);
			unset($db);
			return $result;
		}
	}

	// execute update table
	public function update($table,$colums,$valupdate,$where){
		$colum  = '';
		$ic 	= 0;
		$db = null;
		
		if(count($colums) !== count($valupdate)){
			return false;
		}
		else{
			if($valupdate != null){
			    foreach($colums as $index => $val) {
			        if($colum === '') {
			            $colum = $val.' = ?';
			        } else {
			            $colum .= ','.$val.' = ?';
			        }
			    }
			}else{
				while($ic < count($colums)){
					if($ic+1 >= count($colums)){
						$colum = $colum.$colums[$ic].' = '.$valupdate[$ic];
					}else{
						$colum = $colum.$colums[$ic].' = '.$valupdate[$ic].',';
					}
					$ic++;
				}
			}
			try{
				$db 	 = self::getConnection();
				$query = 'UPDATE '.$table.' SET '.$colum.' WHERE '.$where.'';
				
				$exe   = $db->prepare($query);
				$exe->execute($valupdate);
				$count = $exe->rowCount();
				self::close($db);
				unset($db);
				return $count;
			}catch(PDOException $e){
				$e->getMessage();
				self::close($db);
				unset($db);
				return false;
			}
		}
	}

	public function rawQueryType($type, $query, $value) {
		$result = null;
		$block = explode(' ',$query);
		$db = null;
		if($block == null || $block[0] == ''){
			return false;
		}else{
			if($type === 'select'){
				try{
					$db  		= self::getConnection();
					$exe 		= $db->prepare($query);
					$exe->execute($value);
					$result = $exe->fetchAll(PDO::FETCH_ASSOC);
				    $exe->closeCursor();
					self::close($db);
					unset($db);
					return $result;
				}catch(PDOException $e){
					$e->getMessage();
					self::close($db);
				    unset($db);
				}
			}
			else{
				try{
					$db  		= self::getConnection();
					$exe 		= $db->prepare($query);
					$result     = $exe->execute($value);
					self::close($db);
					unset($db);
					return $result;
				}catch(PDOException $e){
					$e->getMessage();
					self::close($db);
				    unset($db);
					return $result;
				}
			}
		}
	}

	// execute raw query
	public function rawQuery($query, $value, $return_type = 1){
		$result = null;
		$block = explode(' ',$query);
		$db = null;
		if($block == null || $block[0] === ''){
			return false;
		}else{
			if(strtolower($block[0]) === 'select'){
				try{
					$db  		= self::getConnection();
					$exe 		= $db->prepare($query);
					$exe->execute($value);
					$result = $exe->fetchAll(PDO::FETCH_ASSOC);
				    $exe->closeCursor();
					self::close($db);
					unset($db);
					return $result;
				}catch(PDOException $e){
					$e->getMessage();
					self::close($db);
			        unset($db);
				}
			}
			else{
				try{
					$db  		= self::getConnection();
					$exe 		= $db->prepare($query);
					$result     = $exe->execute($value);
					$count      = $exe->rowCount();
					self::close($db);
					unset($db);
					if($return_type === 1) {
					    return $result;
					} else {
					    return $count;
					}
				}catch(PDOException $e){
					$e->getMessage();
					self::close($db);
			        unset($db);
					return $result;
				}
			}
		}
	}
	
	public function recordCount($table, $where = '', $value = array()){
		$result = 0;
		$db = null;
		try{
			$db  = self::getConnection();
			
			if($where != '' || $where != null){
				$where = 'WHERE '.$where;
			}
// 			echo 'SELECT COUNT(1) as records FROM '.$table.' '.$where;
			$exe = $db->prepare('SELECT COUNT(1) as records FROM '.$table.' '.$where);
			$exe->execute($value);
			$result = $exe->fetchAll(PDO::FETCH_ASSOC)[0]['records'];
			$exe->closeCursor();
			self::close($db);
			unset($db);
			return $result;
		}catch(PDOException $e){
			$e->getMessage();
			self::close($db);
			unset($db);
		}
	}
}