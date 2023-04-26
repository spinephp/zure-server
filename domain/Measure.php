<?php
namespace woo\domain;
require_once("domain/domain.php");

class Measure extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("name","password","device","email","firsttime","lasttime","times","secords","version","state"));
	}

	function getName(){
		return $this->objects["name"];
	}

	function setName($data_s){
		$this->objects["name"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
		$this->markDirty();
	}

	function getPassword(){
		return $this->objects["password"];
	}

	function setPassword($data_s){
		$this->objects["password"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
		$this->markDirty();
	}

	function getDevice(){
		return $this->objects["device"];
	}

	function setDevice($data_s){
		$this->objects["device"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
		$this->markDirty();
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setEmail($data_s){
		$this->objects["email"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
		$this->markDirty();
	}

	function setFirsttime($firsttime_s){
		$this->objects["firsttime"] = $this->checkTime($firsttime_s);
		$this->markDirty();
	}

	function getFirsttime(){
		return $this->objects["firsttime"];
	}

	function getLasttime(){
		return $this->objects["lasttime"];
	}

	function setLasttime($lasttime_s){
		$this->objects["lasttime"] = $this->checkTime($lasttime_s);
		$this->markDirty();
	}

	function setTimes($times_s){
		$this->objects["times"] = (int)$times_s;
		$this->markDirty();
	}

	function getTimes(){
		return $this->objects["times"];
	}

	function setSecords($data_s){
		$this->objects["secords"] = (int)$data_s;
		$this->markDirty();
	}

	function getSecords(){
		return $this->objects["secords"];
	}

	function setVersion($data_s){
		$this->objects["version"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
		$this->markDirty();
	}

	function getVersion(){
		return $this->objects["version"];
	}

	function setState($data_s){
		$this->objects["state"] = (int)$data_s;
		$this->markDirty();
	}

	function getState(){
		return $this->objects["state"];
	}
}
?>