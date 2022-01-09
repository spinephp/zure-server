<?php
namespace woo\domain;
require_once("domain/domain.php");

class Level extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("name","password","device","email","firsttime","lasttime","heartinterval","datainterval","version","state"));
	}

	function getName(){
		return $this->objects["name"];
	}

	function setName($data_s){
		$this->objects["name"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getPassword(){
		return $this->objects["password"];
	}

	function setPassword($data_s){
		$this->objects["password"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getDevice(){
		return $this->objects["device"];
	}

	function setDevice($data_s){
		$this->objects["device"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setEmail($data_s){
		$this->objects["email"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function setFirsttime($firsttime_s){
		$this->objects["firsttime"] = $this->checkTime($firsttime_s);
	}

	function getFirsttime(){
		return $this->objects["firsttime"];
	}

	function getLasttime(){
		return $this->objects["lasttime"];
	}

	function setLasttime($lasttime_s){
		$this->objects["lasttime"] = $this->checkTime($lasttime_s);
	}

	function setHeartinterval($data_s){
		$this->objects["heartinterval"] = (int)$data_s;
	}

	function getHeartinterval(){
		return $this->objects["heartinterval"];
	}

	function setDatainterval($data_s){
		$this->objects["datainterval"] = (double)$data_s;
	}

	function getDatainterval(){
		return $this->objects["datainterval"];
	}

	function setVersion($data_s){
		$this->objects["version"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getVersion(){
		return $this->objects["version"];
	}

	function setState($data_s){
		$this->objects["state"] = (int)$data_s;
	}

	function getState(){
		return $this->objects["state"];
	}
}
?>