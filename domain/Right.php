<?php
namespace woo\domain;
require_once("domain/domain.php");

class Right extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("code","name"));
	}

	function setCode($code_s){
		$this->objects["code"] = $code_s;
	}

	function getCode(){
		return $this->objects["code"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}
}
?>