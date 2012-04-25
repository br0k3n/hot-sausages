<?php

	class vimeo extends root {
	
		private $oembed_endpoint = 'http://vimeo.com/api/oembed';
		private $oembed;
	
		/**
		CONSTRUCTOR
		**/
		public function __construct() {
		}
		
		
		private function get_curl($url) {
			$curl = curl_init($url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($curl, CURLOPT_TIMEOUT, 30);
			$return = curl_exec($curl);
			curl_close($curl);
			return $return;
		}
		
		
		public function get_xml($url, $width) {
			$this->oembed = simplexml_load_string($this->get_curl($this->oembed_endpoint.'.xml?url='.rawurlencode($url).'&width='.$width));
		}
		
		
		public function get_video() {
			return html_entity_decode($this->oembed->html);
		}
	
	}

?>