<?php
namespace woo\domain;
require_once("domain/domain.php");

class Bill extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}
}
?>