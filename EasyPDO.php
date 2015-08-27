<?php
class EasyPDO{
	private $db;
	
	function __construct(){
		$MYSQL_HOST  = "localhost";
		$MYSQL_LOGIN = "root";
		$MYSQL_PASS  = "root";
		$MYSQL_DB    = "node_monitor";
		try{
			$this->db = new PDO(
				'mysql:host='.$MYSQL_HOST.';dbname='.$MYSQL_DB,
				$MYSQL_LOGIN,
				$MYSQL_PASS,
				array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING)
			);
		}catch(PDOException $e){
			echo $e->getMessage();
			exit();
		}
		$this->db->exec("SET CHARACTER SET utf8");
	}
	
	function queryDB($sql, $bind=null){
		$stmt = $this->db->prepare($sql);
		if(isset($bind))
			$stmt->execute($bind);
		else
			$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	function executeDB($sql, $bind=null){
		$stmt = $this->db->prepare($sql);
		if(isset($bind))
			$stmt->execute($bind);
		else
			$stmt->execute();
		return $stmt->rowCount();
	}
	
	function closeDB(){
		$this->db = null;
	}
	
	function insertItem($table, $args){
		$fields = array();
		$values = array();
		$bind = array();
		foreach($args as $key=>$value){		
			array_push($fields, $key);
			array_push($values, ':'.$key);
			$bind[':'.$key] = $value;
		}
		$sql = 'INSERT INTO '.$table;
		$sql .= '('.implode(',',$fields).')';
		$sql .= ' VALUES('.implode(',',$values).')';
		
		$this->executeDB($sql,$bind);
		return $this->db->lastInsertId();
	}
	
	function updateItem($table, $args, $where, $whereBind=null){
		$sets = array();
		foreach($args as $key=>$value){
			array_push($sets, $key.'=:'.$key);
			$bind[':'.$key] = $value;
		}
		
		if(isset($whereBind)){
			$bind = array_merge($bind, $whereBind);
		}		
		$sql = 'UPDATE '.$table;
		$sql .= ' SET '.implode(',',$sets);
		$sql .= ' WHERE '.$where;
		
		return $this->executeDB($sql,$bind);
	}
}

?>