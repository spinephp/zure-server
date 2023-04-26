<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class PreciseApp extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","kind","image"));
	}

	function setName($value){
		$this->objects["name"] = htmlentities($value,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setKind($value){
		$this->objects["kind"] = (int)$value;
	}

	function getKind(){
		return $this->objects["kind"];
	}

	function setImage($value){
        if(!$this->isPicture($value))
            throw new \Exception("picture is invalid");
          $this->objects["image"] = $value;
	}

	function getImage(){
		return $this->objects["image"];
	}
}
?>