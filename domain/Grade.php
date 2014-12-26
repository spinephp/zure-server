<?php
namespace woo\domain;
require_once("domain/domain.php");

class Grade extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","cost","image","right","right_en"));
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

	function getNames(){
		return array($this->getName_en(),$this->getName());
	}

	function setCost($cost_s){
		$this->objects["cost"] = (int)$cost_s;
	}

	function getCost(){
		return $this->objects["cost"];
	}

	function setImage($image_s){
		if(!$this->isPicture($image_s))
			throw new \Exception("picture is invalid");
		$this->objects["image"] = $image_s;
	}

	function getImage(){
		return $this->objects["image"];
	}

	function setRight($right_s){
		$this->objects["right"] = htmlentities($right_s,ENT_QUOTES,'UTF-8');
	}

	function getRight(){
		return $this->objects["right"];
	}

	function setRight_en($right_s){
		$this->objects["right_en"] = htmlentities($right_s,ENT_QUOTES,'UTF-8');
	}

	function getRight_en(){
		return $this->objects["right_en"];
	}

	function getRights(){
		return array($this->objects["right"],$this->objects["right_en"]);
	}
}
?>