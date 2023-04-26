<?php
namespace woo\domain;
require_once("domain/domain.php");

class MeasurelMessage extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("type","title","title_en","body","body_en","params","starttime","endtime","state"));
	}

	function setType($data_s){
		$this->objects["type"] = (int)$data_s;
	}

	function getType(){
		return $this->objects["type"];
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

    function getTitles() {
        return array($this->getTitle_en(), $this->getTitle());
    }

	function setBody($name_s){
		$this->objects["body"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getBody(){
		return $this->objects["body"];
	}

	function setBody_en($name_s){
		$this->objects["body_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getBody_en(){
		return $this->objects["body_en"];
	}

    function getBodys() {
        return array($this->getBody_en(), $this->getBody());
    }

	function setParams($name_s){
		$this->objects["params"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getParams(){
		return $this->objects["params"];
	}

	function setStarttime($lasttime_s){
		$this->objects["starttime"] = $this->checkTime($lasttime_s);
	}

	function getStarttime(){
		return $this->objects["starttime"];
	}

	function setEndtime($lasttime_s){
		$this->objects["endtime"] = $this->checkTime($lasttime_s);
	}

	function getEndtime(){
		return $this->objects["endtime"];
	}

	function setState($data_s){
		$this->objects["state"] = (int)$data_s;
	}

	function getState(){
		return $this->objects["state"];
	}
}
?>