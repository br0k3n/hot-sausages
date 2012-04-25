<?php

	class db extends root {
	
		/**
		CONSTRUCTOR
		**/
		public function __construct() {
   		}
   		
		
		/**
		CONNECT TO DB
		**/
		private static function connect() {
			@$mysqli = new mysqli(HOST, USER, PASSWD, DB);
			$mysqli->set_charset("utf8");
			return $mysqli;	
		}
		
		
		/**
		SELECT DB FIELDS WITH CONDITIONS
		**/
		public function selectBy($table, $fields, $conditions = "1", $options = "") {
			$mysqli = self::connect();
			
			$query = "SELECT ";
			
			if(is_array($fields))
				$query .= implode(", ", $fields);			
			else
				$query .= $fields;
						
			$query .= " FROM ".$table." WHERE ";
			
			$where = array();
			
			if(is_array($conditions)) {
				while(list($key, $val) = each($conditions)) {
					$where[] = $key." = '".$this->clearData($mysqli, $val)."'";
				}
				$query .= implode(" AND ", $where);
				
			}
			else
				$query .= $conditions;
				
			$query .= " ".$options;
			
			$result = $mysqli->query($query);
			
			while($row = $result->fetch_assoc()) {
				$arr[] = $row;
			}
			
			self::deconnect($mysqli);
			
			return $arr;
		}
		
		
		/**
		SELECT SINGLE DB FIELD BY ID
		**/
		public function selectById($table, $id) {
			$mysqli = self::connect();
						
			//Use SHOW INDEX FROM <table> to auto detect primary key
			$primary = $mysqli->query("SHOW INDEX FROM ".$table);
			
			$primary = $primary->fetch_assoc();
						
			$result = $mysqli->query("SELECT * FROM ".$table." WHERE ".$primary['Column_name']." = ".$this->clearData($mysqli, $id));
			
			$row = $result->fetch_assoc();
			
			self::deconnect($mysqli);
			
			return $row;	
		}
		
		
		/**
		SELECT ALL DB FIELDS
		**/
		public function selectAll($table) {
			$mysqli = self::connect();
			
			$result = $mysqli->query("SELECT * FROM ".$table);
			
			while($row = $result->fetch_assoc()) {
				$arr[] = $row;
			}
			
			self::deconnect($mysqli);
			
			return $arr;
			
		}
		
		
		/**
		INSERT DB FIELD
		**/
		public function insert($table, $arr) {
			$mysqli = self::connect();
			
			$columns = array();
			$values = array();
			
			while(list($key, $val) = each($arr)) {
				$columns[] = $key;
				$values[] = $this->clearData($mysqli, $val);
			}
			
			$query = "INSERT INTO ".$table." (";
			$query .= implode(", ", $columns);
			$query .= ") VALUES ('";
			$query .= implode("', '", $values);
			$query .= "')";
			
			$mysqli->query($query);
			
			$id = $mysqli->insert_id;
			
			self::deconnect($mysqli);
			
			return $id;
		}
		
		
		/**
		UPDATE DB FIELD
		**/
		public function update($table, $arr1, $arr2) {
			$mysqli = self::connect();
			
			$query = "UPDATE ".$table." SET ";
			$set = array();
			
			while(list($key, $val) = each($arr1)) {
				$set[] = $key." = '".$this->clearData($mysqli, $val)."'";
			}
			
			$query .= implode(", ", $set);
			
			$query .= " WHERE ";
			
			$where = array();
						
			while(list($key, $val) = each($arr2)) {
				$where[] = $key." = '".$this->clearData($mysqli, $val)."'";
			}
			
			$query .= implode(" AND ", $where);
			
			$bool = $mysqli->query($query);
			
			self::deconnect($mysqli);
			
			return $bool;
		}
		
		
		/**
		DELETE DB FIELD BY ID
		**/
		public function delete($table, $id) {
			$mysqli = self::connect();
			
			//Use SHOW INDEX FROM <table> to auto detect primary key
			$primary = $mysqli->query("SHOW INDEX FROM ".$table);
			
			$primary = $primary->fetch_assoc();
			
			$id = $this->clearData($mysqli, $id);
			
			$query = "DELETE FROM ".$table." WHERE ".$primary['Column_name'].' = '.$id;
			
			$bool = $mysqli->query($query);
			
			self::deconnect($mysqli);
			
			return $bool;
			
		}

		public function query($query) {
			$mysqli = self::connect();
			
			$result = $mysqli->query($query);
			
			if(is_object($result)) {
				while($row = $result->fetch_assoc()) {
					$arr[] = $row;
				}
				
				self::deconnect($mysqli);
				
				return $arr;
			} else
				return $result;
		}

		/**
		CLOSE CONNECTION
		**/
		private static function deconnect($mysqli) {
			$mysqli->close();
		}
		
		/**
		DESTRUCTOR
		**/
		public function __destruct() {
		}
		
		/**
		STUFF
		**/
		
		/* TODO */
		/*private static function alterTable($table, $arr) {
			$mysqli = self::connect();
			
			$result = $mysqli->$query = "SHOW COLUMNS FROM ".$table;
			
			$row = $result->fetch_assoc();
			
			
			
			$query = "ALTER TABLE ".$table." ADD "..;
		}*/
		
		/* Remove special chars and prevent sql injections */
		private function clearData($mysqli, $val) {
			$search = array("delete", "update", "insert", "drop", "alter", "=", "select");
			$replace = "";
		
			return str_ireplace($search, $replace, $mysqli->real_escape_string($val));
		}
		
		protected static function convertDate($date) {
			return date("j/m/Y", strtotime($date));
		}
		
	}

?>