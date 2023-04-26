<?php
namespace woo\domain;
require_once("domain/domain.php");

class Drydata extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("mainid","time","settingtemperature","temperature","mode"));
	}

	function setMainid($id_s){
		$this->objects["mainid"] = (int)$id_s;
	}

	function getMainid(){
		return $this->objects["mainid"];
	}

	function setTime($time_s){
		$this->objects["time"] = (int)$time_s;
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setSettingtemperature($temperature_s){
		$this->objects["settingtemperature"] = (int)$temperature_s;
	}

	function getSettingtemperature(){
		return $this->objects["settingtemperature"];
	}

	function setTemperature($temperature_s){
		$this->objects["temperature"] = (int)$temperature_s;
	}

	function getTemperature(){
		return $this->objects["temperature"];
	}

	function setMode($mode_s){
		$this->objects["mode"] = (int)$mode_s;
	}

	function getMode(){
		return $this->objects["mode"];
	}
}
?>