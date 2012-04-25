<?php

	class document extends root {
	
		protected $tmp = TMP_UPLOAD;
	
		/**
		CONSTRUCTOR
		**/
		public function __construct() {
   		}
   		
   		/**
   		EVALUATE FILE
   		**/
   		public function evaluateDoc($mime) {
   			
   			$arr = array("image/jpeg", "image/gif", "image/png");
   			
   			if(in_array($mime, $arr))
   				return new image();
   			else
   				return new file();
   		}
   		
   		/**
   		RENAME FILES
   		**/
   		protected function rename($name) {
			return substr(md5(crypt(time().$name."pippo")), 9, 17)."_".$name;
		}
	
	
		/**
		MOVE FROM LOCAL SYSTEM FOLDER TO TMP FOLDER
		**/
		public function moveToLocal($path) {
			move_uploaded_file($path, $this->tmp);
		}
		
		/**
		DELETE FILE
		**/
		public function delete($path) {
			
			if(file_exists($path) && is_file($path))
				unlink($path);
		}
		
		
		/**
		DESTRUCTOR
		**/
		public function __destruct() {
		}
	
	}

?>