<?php
namespace woo\domain;
require_once("domain/domain.php");

class EvalReply extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("evalid","userid","parentid","content","time"));
	}

	function setEvalid($evalid_s){
		$this->objects["evalid"] = (int)$evalid_s;
	}

	function getEvalid(){
		return $this->objects["evalid"];
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setContent($askcontent_s){
		$this->objects["content"] = htmlentities($askcontent_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setTime($asktime_s){
		$this->objects["time"] = $this->checkTime($asktime_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setParentid($parentid_s){
		$this->objects["parentid"] = (int)$parentid_s;
	}

	function getParentid(){
		return $this->objects["parentid"];
	}
}
?>