<?php

	class object extends root {
	
		private $db;
		private $objTable;
		private $document;
		
		/**
		CONSTRUCTOR
		**/
		public function __construct($table) {
		
			$this->db = new db();
			$this->objTable = $table;
			$this->document = new document();
		
   		}
   		
   		
   		/**
   		FILTER DATA
   		**/
   		public function filter($arr) {
   			
   			$filtered = unserialize(FILTER);
   			
   			while(list($key, $val) = each($filtered)) {
   				unset($arr[$val]);
   			}
   			
   			return $arr;
   		}
   		
		
		/**
		SELECT SINGLE OBJ BY ID
		**/
		public function selectSingleById($id) {
			
			$row = $this->db->selectById($this->objTable, $id);	
			
			return $row;
		}
		
		
		/**
		SELECT ALL OBJ
		**/
		public function selectAll() {
			
			$row = $this->db->selectAll($this->objTable);
			
			return $row;
		}
		
		/**
		SELECT ALL OBJ FILTERED
		**/
		public function selectAllFiltered($conditions = "1", $options = "") {
		
			$row = $this->db->selectBy($this->objTable, "*", $conditions, $options);
			
			return $row;
		}
		
		
		/**
		INSERT SINGLE OBJ
		**/
		public function insertSingleObj($fields, $files = array()) {
				
			while(list($key, $val) = each($files)) {
				
				for($i=0; $i<count($val['name']); $i++) {
					if(is_uploaded_file($val['tmp_name'][$i])) {

						$this->document->moveToLocal($val['tmp_name'][$i]);
						
						$result = $this->document->evaluateDoc($val['type'][$i]);
						
						$file_name = $result->upload($val['name'][$i]);
						
						$files_arr[] = $file_name;
					
					}
				}
			}
					
			$id = $this->db->insert($this->objTable, $this->filter($fields));
			
			foreach($files_arr as $path) {
				$uploads = array('files_obj_table' => $this->objTable, 'files_obj_id' => $id, 'files_path' => $path);
				$this->db->insert(FILESTAB, $this->filter($uploads));
			}
			return $id;
		}
		
		
		/**
		UPDATE SINGLE OBJ
		**/
		public function updateSingleObj($fields, $conditions, $files = array()) {
		
			while(list($key, $val) = each($files)) {
			
				if(is_uploaded_file($val['tmp_name'])) {
				
					$this->document->moveToLocal($val['tmp_name']);
					
					$result = $this->document->evaluateDoc($val['type']);

					$file_name = $result->upload($val['name']);
					
					$fields[$key] = $file_name;
				
				}
			}
						
			return $this->db->update($this->objTable, $fields, $conditions);
		}
		
		
		/**
		DELETE SINGLE OBJ BY ID
		**/
		public function deleteObjById($id) {
			
			return $this->db->delete($this->objTable, $id);
			
		}
		
		
		/**
		DESTRUCTOR
		**/
		public function __destruct() {
		}
	
	}

?>