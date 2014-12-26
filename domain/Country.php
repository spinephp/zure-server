<?php
namespace woo\domain;
require_once("domain/domain.php");

class Country extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("code2","code3","number","name","name_en"));
	}

	function setCode2($code2_s){
    if (!preg_match('/^[A-Z]{2}$/',$code2_s))
      throw new \Exception("Code is invalid");
		$this->objects["code2"] = $code2_s;
	}

	function getCode2(){
		return $this->objects["code2"];
	}

	function setCode3($code3_s){
    if (!preg_match('/^[A-Z]{3}$/',$code3_s))
      throw new \Exception("Code is invalid");
		$this->objects["code3"] = $code3_s;
	}

	function getCode3(){
		return $this->objects["code3"];
	}

	function setNumber($number_s){
    if (!preg_match('/^[0-9]{1,3}$/',$number_s))
      throw new \Exception("Code is invalid");
		$this->objects["number"] = $number_s;
	}

	function getNumber(){
		return $this->objects["number"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setName_en($name_en_s){
		$this->objects["name_en"] = htmlentities($name_en_s,ENT_QUOTES,'UTF-8');
	}

	function getName_en(){
		return $this->objects["name_en"];
	}

	function getNames(){
		return array($this->getName_en(),$this->getName());
	}
}
?>