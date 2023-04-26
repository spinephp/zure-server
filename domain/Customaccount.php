<?php
namespace woo\domain;
require_once("domain/domain.php");

class CustomAccount extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","in","out","time","note"));
	}
	
	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setIn($in_s){
		$this->objects["in"] = (float)$in_s;
	}

	function getIn(){
		return $this->objects["in"];
	}

	function setOut($out_s){
		$this->objects["out"] = (float)$out_s;
	}

	function getOut(){
		return $this->objects["out"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}
}
?>