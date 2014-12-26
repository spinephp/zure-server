<?php
namespace woo\domain;
require_once("domain/domain.php");

class News extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("title","title_en","content","content_en","time"));
	}

	function setTitle($name_s){
		$this->objects["title"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getTitle(){
    return $this->objects["title"];
	}

	function setTitle_en($name_s){
		$this->objects["title_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getTitle_en(){
    return $this->objects["title_en"];
	}

	function getTitles(){
    return array($this->getTitle_en(),$this->getTitle());
	}

	function setContent($content_s){
		$this->objects["content"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
    return $this->objects["content"];
	}

	function setContent_en($content_s){
		$this->objects["content_en"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
	}

	function getContent_en(){
    return $this->objects["content_en"];
	}

	function getContents(){
    return array($this->getContent_en(),$this->getContent());
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>