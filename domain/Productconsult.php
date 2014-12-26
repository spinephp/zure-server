<?php
namespace woo\domain;
require_once("domain/domain.php");

class ProductConsult extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("proid","userid","type","content","time","reply","replytime"));
	}

	function setProid($proid_s){
		$this->objects["proid"] = (int)$proid_s;
	}

	function getProid(){
		return $this->objects["proid"];
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setType($type_s){
		$this->objects["type"] = (int)$type_s;
	}

	function getType(){
		return $this->objects["type"];
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

	function setReply($reply_s){
		$this->objects["reply"] = htmlentities($reply_s,ENT_QUOTES,'UTF-8');
	}

	function getReply(){
		return $this->objects["reply"];
	}

	function setReplytime($replytime_s){
		$this->objects["replytime"] = $this->checkTime($replytime_s);
	}

	function getReplytime(){
		return $this->objects["replytime"];
	}
}
?>