<?php
namespace woo\domain;
require_once("domain/domain.php");

class Dryline extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("lineno","temperature","time"));
	}

	function setTemperature($temperature_s){
		$this->objects["temperature"] = (int)$temperature_s;
	}

	function getTemperature(){
		return $this->objects["temperature"];
	}

	function setLineno($no_s){
		$this->objects["lineno"] = (int)$no_s;
	}

	function getLineno(){
		return $this->objects["lineno"];
	}

	function setTime($time_s){
		$this->objects["time"] = (int)$time_s;
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>