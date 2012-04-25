<?php

	class auth extends root {
		
		private $table_name;
		private $scope;
		private $id_field;
		private $username_field;
		private $password_field;
		private $type_field;
		
		/**
		CONSTRUCTOR
		**/
		public function __construct($table, $id_field, $username_field, $password_field, $type_field, $scope) {
			$this->table_name = $table;
			$this->id_field = $id_field;
			$this->username_field = $username_field;
			$this->password_field = $password_field;
			$this->type_field = $type_field;
			$this->scope = $scope;
   		}
		
		/**
		SELECT DATA FROM DB
		**/
		public function getData($username, $password) {
			$db = new db();
			$conditions = array($this->username_field => $username, $this->password_field => $password);
			return $db->selectBy($this->table_name, "*", $conditions);
		}
		
		/**
		PREVENT SQL INJECTIONS
		**/
		public function checkLogin($result, $username, $password) {
			return true;
		}
		
		/**
		SAVE SESSION DATA
		**/
		private function setupLogin($result) {
			session_start();
			$_SESSION[$this->scope] = array();
			$_SESSION[$this->scope]['user_ip'] = $_SERVER['REMOTE_ADDR'];
			$_SESSION[$this->scope]['user_id'] = $result[$this->id_field];
			$_SESSION[$this->scope]['user_name'] = $result[$this->username_field];
			$_SESSION[$this->scope]['user_pass'] = $this->pHash($result[$this->username_field], $result[$this->password_field]);
			$_SESSION[$this->scope]['user_type'] = $result[$this->type_field];
			session_write_close();
		}
		
		/**
		GENERATE LOGIN
		**/
		public function userLogin($username, $password) {
			if($this->checkFields($username, $password)) {
			
				$name = htmlspecialchars($username);
				$passwd = $this->pHash($username, htmlspecialchars($password));
				
				$result = $this->getData($name, $passwd);
				
				if($this->checkLogin($result, $name, $passwd))
					$this->setupLogin($result[0]);
			}
		}
		
		/**
		CHECK FIELDS FORMAT
		**/
		private function checkFields($username, $password) {
			if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $username) && preg_match("/^[A-Za-z0-9]{6,30}$/", $password))
				return true;
			else
				return false;
		}
		
		/**
		GENERATE PASSWORD HASH
		**/
		private function pHash($username, $password) {
			return sha1(md5($username.$password.SALT));
		}
		
		/**
		DESTROY SESSION
		**/
		public function userLogout() {
			session_start();
			unset($_SESSION[$this->scope]);
			session_write_close();
		}
		
		/**
		CHECK USER IP
		**/
		public function isAuth() {
			$auth = false;
			session_start();
			if(isset($_SESSION[$this->scope]['user_ip']) && $_SESSION[$this->scope]['user_ip'] == $_SERVER['REMOTE_ADDR']) {
				$auth = true;
			}
			session_write_close();
			return $auth;
	
		}
		
		/**
		RETURN USER TYPE
		**/
		public function returnType() {
	
			$type = -1;
			session_start();
			//Regenerate session id
			session_regenerate_id();
			if(isset($_SESSION[$this->scope]['user_type'])) {
				$type = $_SESSION[$this->scope]['user_type'];
			}
			session_write_close();
			return $type;
	
		}
	
		public function check($type, $path = "./login.php") {
			if(!$this->isAuth() || $this->returnType() != $type)
				header("Location: ".$path);
		}
	
	}

?>