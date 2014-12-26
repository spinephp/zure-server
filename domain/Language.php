<?php
namespace woo\domain;
require_once("domain/domain.php");

class Language extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
    return $this->objects["name"];
  }

	function setName_en($name_s){
		$this->objects["name_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName_en(){
    return $this->objects["name_en"];
  }
}
?>