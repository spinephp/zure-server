<?php
namespace woo\domain;
require_once("domain/domain.php");

class Departmentright extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("departmentid","name","bit",'time'));
	}

	function setDepartmentid($departmentid_s){
		$this->objects["departmentid"] = (int)$departmentid_s;
	}

	function getDepartmentid(){
		return $this->objects["departmentid"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setBit($bit_s){
		$this->objects["bit"] = (int)$bit_s;
	}

	function getBit(){
		return $this->objects["bit"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>