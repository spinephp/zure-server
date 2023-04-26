<?php
namespace woo\domain;
require_once("domain/domain.php");

class PreciseDevice extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("deviceid","active","firsttime","lasttime","times"));
	}

	function setDeviceid($deviceid_s){
		$this->objects["deviceid"] = htmlentities($deviceid_s,ENT_QUOTES,'UTF-8');
	}

	function getDeviceid(){
		return $this->objects["deviceid"];
	}

	function setActive($data_s){
		$this->objects["active"] = (int)$data_s;
	}

	function getActive(){
		return $this->objects["active"];
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

	function setTimes($times_s){
		$this->objects["times"] = (int)$times_s;
	}

	function getTimes(){
		return $this->objects["times"];
	}
}
?>