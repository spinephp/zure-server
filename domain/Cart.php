<?php
namespace woo\domain;
require_once("domain/domain.php");

class Cart extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","proid","number","time"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setProid($proid_s){
		$this->objects["proid"] = (int)$proid_s;
	}

	function getProid(){
		return $this->objects["proid"];
	}

	function setNumber($number_s){
		$this->objects["number"] = (int)$number_s;
	}

	function getNumber(){
		return $this->objects["number"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>