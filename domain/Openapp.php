<?php
namespace woo\domain;
require_once("domain/domain.php");

class OpenApp extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("did","appid","time","latitude","longitude"));
	}

	function setDid($value_s){
		$this->objects["did"] = (Int)$value_s;
	}

	function getDid(){
    return $this->objects["did"];
	}

	function setAppid($value_s){
		$this->objects["appid"] = (Int)$value_s;
	}

	function getAppid(){
    return $this->objects["appid"];
	}

	function setLatitude($value_s){
		$this->objects["latitude"] = (double)$value_s;
	}

	function getLatitude(){
    return $this->objects["latitude"];
	}

	function setLongitude($value_s){
		$this->objects["longitude"] = (double)$value_s;
	}

	function getLongitude(){
    return $this->objects["longitude"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>