<?php

	class image extends document {
	
		private $handler;
		private $width;
		private $height;
		private $mime;
		private $path = IMAGES_UPLOAD;
		
		
		/**
		CREATE IMAGE HANDLER
		**/
		private function createHandler() {
			
			if($this->mime == "image/jpeg")
			$this->handler = imagecreatefromjpeg($this->tmp);

			else if($this->mime == "image/gif")
				$this->handler = imagecreatefromgif($this->tmp);
	
			else if($this->mime == "image/png")
				$this->handler = imagecreatefrompng($this->tmp);
		}


		/**
		DEFINE IMAGE
		**/
		private function setImage($path) {
			
			list($this->width, $this->height, $this->mime) = getimagesize($path);
			$this->mime = image_type_to_mime_type($this->mime);	
			
			$this->createHandler();
		}
		
		
		/**
		DESTROY IMAGE HANDLER
		**/
		private function unsetHandler() {
			
			imagedestroy($this->handler);
		}
		
		
		/**
		UPLOAD IMAGE TO GIVEN PATH
		**/
		public function upload($name) {
		
			$images = unserialize(IMAGES);
		
			$this->setImage($this->tmp);
			
			$this->createThumb($images['scale'], $images['width'], $images['height']);
		
			$tmp = $this->rename($name);
		
			$this->path .= $tmp;
		
			if($this->mime == "image/jpeg")
				imagejpeg($this->handler, $this->path, 80);

			else if($this->mime == "image/gif")
				imagegif($this->handler, $this->path);
	
			else if($this->mime == "image/png")
				imagepng($this->handler, $this->path);
				
			$this->unsetHandler();
			
			return $tmp;
			
		}
		
		
		/**
		BASIC RESIZE IMAGE
		**/
		private function resize($width, $height) {
		
			//create a canvans to paste handled image
			$tmp = imagecreatetruecolor($width,$height);
			//activate alpha cannel on paste
			imagealphablending($tmp, false);
			
			//resize image
			imagecopyresampled($tmp, $this->handler, 0, 0, 0, 0, $width, $height, $this->width, $this->height);
			//set the flag to save full alpha channel informatio
			imagesavealpha($tmp, true);
			
			$this->handler = $tmp;
			$this->width = $width;
			$this->height = $height;
		}
		
		
		/**
		SCALE IMAGE BY WIDTH
		**/
		private function scaleByWidth($width) {
			
			$resize_factor = $this->width / $width;
			$height = round($this->height / $resize_factor);
			
			$this->resize($width,$height);
		}
		
		
		/**
		SCALE IMAGE BY HEIGHT
		**/
		private function scaleByHeight($height) {
		
			$resize_factor = $this->height / $height;
			$width = round($this->width / $resize_factor);
			
			$this->resize($width,$height);
		}
		
		/**
		SCALE IMAGE BY MAX WIDTH OR MAX HEIGHT
		**/
		private function scaleMax($width, $height) {
			
			if($width <= $height && $width <= $this->width)
				$this->scaleByWidth($width);
			elseif($height <= $this->height)
				$this->scaleByHeight($height);
			else
				$this->resize($this->width, $this->height);
		}
		
		
		/**
		RESIZE FUNCTION
		**/
		private function createThumb($type, $width = 0, $height = 0) {
			
			switch($type) {
				case "scaleByWidth": $this->scaleByWidth($width) ; break;
				case "scaleByHeight": $this->scaleByHeight($height); break;
				case "scaleMax": $this->scaleMax($width, $height); break;
			}
			
		}
		
	}

?>