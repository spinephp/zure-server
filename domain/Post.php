<?php
namespace woo\domain;
require_once("domain/domain.php");

class Post extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","departmentids","right"));
	}

	function setDepartmentids($departmentids_s){
    if (!preg_match('/^([0-9][0-9]){0,10}$/', $departmentids_s))
      throw new \Exception("Post ids is invalid");
		$this->objects["departmentids"] = $departmentids_s;
	}

	function getDepartmentids(){
		return $this->objects["departmentids"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setRight($right_s){
		$this->objects["right"] = (int)$right_s;
	}

	function getRight(){
		return $this->objects["right"];
	}
}
?>