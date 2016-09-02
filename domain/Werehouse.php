<?php
namespace woo\domain;
require_once("domain/domain.php");

class Werehouse extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","size","unit","number","picture","note"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setSize($size_s){
		$this->objects["size"] = htmlentities($size_s,ENT_QUOTES,'UTF-8');
	}

	function getSize(){
		return $this->objects["size"];
	}

	function setUnit($unit_s){
		$this->objects["unit"] = htmlentities($unit_s,ENT_QUOTES,'UTF-8');
	}

	function getUnit(){
		return $this->objects["unit"];
	}

	function setNumber($number_s){
		$this->objects["number"] = (float)$number_s;
	}

	function getNumber(){
		return $this->objects["number"];
	}

	function setPicture($picture_s){
		if(!$this->isPicture($picture_s))
			throw new \Exception("picture is invalid");
		$this->objects["picture"] = $picture_s;
	}

	function getPicture(){
		return $this->objects["picture"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}
}
?>