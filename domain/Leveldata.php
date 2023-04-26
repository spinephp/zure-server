<?php
namespace woo\domain;
require_once("domain/domain.php");

class LevelData extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("levelid","x","y","time"));
	}

	function getLevelid(){
		return $this->objects["levelid"];
	}

	function setLevelid($data_s){
		$this->objects["levelid"] = (int)$data_s;
	}

	function getX(){
		return $this->objects["x"];
	}

	function setX($data_s){
		$this->objects["x"] = (float)$data_s;
	}

	function getY(){
		return $this->objects["y"];
	}

	function setY($data_s){
		$this->objects["y"] = (float)$data_s;
	}

	function setTime($firsttime_s){
		$this->objects["time"] = (int)$firsttime_s; //$this->checkTime($firsttime_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>