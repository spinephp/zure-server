<?php
namespace woo\domain;
require_once("domain/domain.php");

class Physicoindex extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","unit","operator","value","environment"));
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

	function setUnit($unit_s){
		$this->objects["unit"] = htmlentities($unit_s,ENT_QUOTES,'UTF-8');
	}

	function getUnit(){
		return html_entity_decode($this->objects["unit"],ENT_QUOTES,'UTF-8');
	}

	function setOperator($operator_s){
		if($operator_s!='>' && $operator_s!='<')
			throw new \Exception("Operator is invalid");
		$this->objects["operator"] = $operator_s;
	}

	function getOperator(){
		return $this->objects["operator"];
	}

	function setValue($value_s){
		$this->objects["value"] = (float)$value_s;
	}

	function getValue(){
		return $this->objects["value"];
	}

	function getValues(){
		return array($this->objects["value"],$this->objects["value"]);
	}

	function setEnvironment($environment_s){
		$this->objects["environment"] = htmlentities($environment_s,ENT_QUOTES,'UTF-8');
	}

	function getEnvironment(){
		return $this->objects["environment"];
	}
}
?>