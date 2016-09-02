<?php
namespace woo\domain;
require_once("domain/domain.php");

class Orderpackingtraycarton extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("orderid","trayid","traynum","cartonid","cartonnum","numincarton","sumnum"));
	}

	function setOrderid($orderid_s){
		$this->objects["orderid"] = (int)$orderid_s;
	}

	function getOrderid(){
		return $this->objects["orderid"];
	}

	function setTrayid($trayid_s){
		$this->objects["trayid"] = (int)$trayid_s;
	}

	function getTrayid(){
		return $this->objects["trayid"];
	}

	function setTraynum($traynum_s){
		$this->objects["traynum"] = (int)$traynum_s;
	}

	function getTraynum(){
		return $this->objects["traynum"];
	}

	function setCartonid($cartonid_s){
		$this->objects["cartonid"] = (int)$cartonid_s;
	}

	function getCartonid(){
		return $this->objects["cartonid"];
	}

	function setCartonnum($cartonnum_s){
		$this->objects["cartonnum"] = (int)$cartonnum_s;
	}

	function getCartonnum(){
		return $this->objects["cartonnum"];
	}

	function setNumincarton($numincarton_s){
		$this->objects["numincarton"] = (int)$numincarton_s;
	}

	function getNumincarton(){
		return $this->objects["numincarton"];
	}

	function setSumnum($sumnum_s){
		$this->objects["sumnum"] = (int)$sumnum_s;
	}

	function getSumnum(){
		return $this->objects["sumnum"];
	}
}
?>