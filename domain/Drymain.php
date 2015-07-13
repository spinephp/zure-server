<?php
namespace woo\domain;
require_once("domain/domain.php");

class Drymain extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("starttime","lineno","state"));
	}

	function setStarttime($time_s){
		$this->objects["starttime"] = htmlentities($time_s,ENT_QUOTES,'UTF-8');
	}

	function getStarttime(){
		return $this->objects["starttime"];
	}

	function setLineno($no_s){
		$this->objects["lineno"] = (int)$no_s;
	}

	function getLineno(){
		return $this->objects["lineno"];
	}

	function setState($no_s){
		$this->objects["state"] = (int)$no_s;
	}

	function getState(){
		return $this->objects["state"];
	}
}
?>