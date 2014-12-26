<?php
namespace woo\domain;
require_once("domain/domain.php");

class Currency extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","abbreviation","symbol","exchangerate"));
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

	function setAbbreviation($abbreviation_s){
		if(!in_array($abbreviation_s,array("CNY","USD"),true))
		  throw new \Exception("Invalid abbreviation");
		$this->objects["abbreviation"] = $abbreviation_s;
	}

	function getAbbreviation(){
    return $this->objects["abbreviation"];
  }

	function setSymbol($symbol_s){
		if(strpos("¥$",$symbol_s)===false)
		  throw new \Exception("Invalid symbol");
		$this->objects["symbol"] = $symbol_s;
	}

	function getSymbol(){
		return $this->objects["symbol"];
	}

	function setExchangerate($exchangerate_s){
		$this->objects["exchangerate"] = (float)$exchangerate_s;
	}

	function getExchangerate(){
		return $this->objects["exchangerate"];
	}
}
?>