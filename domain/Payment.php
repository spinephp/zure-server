<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Payment extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","note","url","urltext"));
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

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}

	function setUrl($url_s){
		if(!filter_var($url_s, FILTER_VALIDATE_URL))
			throw new \Exception("Url is invalid");
		$this->objects["url"] = $url_s;
	}

	function getUrl(){
		return $this->objects["url"];
	}

	function setUrltext($urltext_s){
		$this->objects["urltext"] = htmlentities($urltext_s,ENT_QUOTES,'UTF-8');
	}

	function getUrltext(){
		return $this->objects["urltext"];
	}
}
?>