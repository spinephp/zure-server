<?php
namespace woo\domain;
require_once("domain/domain.php");

class Levelalarm extends DomainObject{
	function __construct($array){
		parent::__construct($array,array("levelid","alarm","type","x0","y0","x1","y1","width","radius","area","sound","flash","vibrate","time"));
	}

	function getLevelid(){
		return $this->objects["levelid"];
	}

	function setLevelid($data_s){
		$this->objects["levelid"] = (int)$data_s;
	}

	function getAlarm(){
		return $this->objects["alarm"];
	}

	function setAlarm($data_s){
		$this->objects["alarm"] = (int)$data_s;
	}

	function getType(){
		return $this->objects["type"];
	}

	function setType($data_s){
		$this->objects["type"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getX0(){
		return $this->objects["x0"];
	}

	function setX0($data_s){
		$this->objects["x0"] = (float)$data_s;
	}

	function getY0(){
		return $this->objects["y0"];
	}

	function setY0($data_s){
		$this->objects["y0"] = (float)$data_s;
	}

	function getX1(){
		return $this->objects["x1"];
	}

	function setX1($data_s){
		$this->objects["x1"] = (float)$data_s;
	}

	function getY1(){
		return $this->objects["y1"];
	}

	function setY1($data_s){
		$this->objects["y1"] = (float)$data_s;
	}

	function getWidth(){
		return $this->objects["width"];
	}

	function setWidth($data_s){
		$this->objects["width"] = (float)$data_s;
	}

	function getRadius(){
		return $this->objects["radius"];
	}

	function setRadius($data_s){
		$this->objects["radius"] = (float)$data_s;
	}

	function getArea(){
		return $this->objects["area"];
	}

	function setArea($data_s){
		$this->objects["area"] = (int)$data_s;
	}

	function getSound(){
		return $this->objects["sound"];
	}

	function setSound($data_s){
		$this->objects["sound"] = (int)$data_s;
	}

	function getFlash(){
		return $this->objects["flash"];
	}

	function setFlash($data_s){
		$this->objects["flash"] = (int)$data_s;
	}

	function getVibrate(){
		return $this->objects["vibrate"];
	}

	function setVibrate($data_s){
		$this->objects["vibrate"] = (int)$data_s;
	}

	function setTime($firsttime_s){
		$this->objects["time"] = $this->checkTime($firsttime_s);
	}

	function getTime(){
		return $this->objects["time"];
	}
}
?>