<?php
class carbonData {
	var $caid;
	var $carbonGram;
	var $source;
	var $country;
	
	function __construct($caid, $carbonGram, $source, $country) {
		$this->caid = $caid;
		$this->carbonGram = $carbonGram;
		$this->source = $source;
		$this->country = $country;
	}
}
?>