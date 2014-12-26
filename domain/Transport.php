<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Transport extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","note","charges"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}

	function setCharges($charges_s){
		$this->objects["charges"] = (float)$charges_s;
	}

	function getCharges(){
		return $this->objects["charges"];
	}
}
?>