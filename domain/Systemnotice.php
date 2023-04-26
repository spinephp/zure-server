<?php
namespace woo\domain;
require_once("domain/domain.php");

class SystemNotice extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","type","content","time","readstate"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setType($type_s){
		if($type_s!='E' && $type_s != 'G')
			throw new \Exception("The code of type is invalid");
		$this->objects["type"] = $type_s;
	}

	function getType(){
		return $this->objects["type"];
	}

	function setContent($content_s){
		$this->objects["content"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setReadstate($readstate_s){
		$this->objects["readstate"] = (int)$readstate_s;
	}

	function getReadstate(){
		return $this->objects["readstate"];
	}
}
?>