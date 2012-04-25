<?php

	class file extends document {
	
		private $path = FILES_UPLOAD;
	 
		/**
		UPLOAD FILE
		**/
		public function upload($name) {
			$tmp = $this->rename($name);
			$this->path .= $tmp;
			copy($this->tmp, $this->path);
			
			return $tmp;
		}
		
	}

?>