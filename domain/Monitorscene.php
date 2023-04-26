<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class MonitorScene extends DomainObject{

	private $_saltLength = 7;
	
	function __construct($array){
		parent::__construct($array,array("sid","name","password","device","countin","countout","state"));
	}
	
	function setName($name_s){
    if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9\-\_\@\.]{2,18}[a-zA-Z0-9]{1}$/', $name_s))
      throw new \Exception("Name is invalid");
		$this->objects["name"] = $name_s;
	}

	function getName(){
		return $this->objects["name"];
	}

	function setPassword($password_s){
		$this->objects["password"] = (strlen($password_s)!=(40+$this->_saltLength))? $this->_getSaltedHash($password_s):$password_s;
	}

	function getPassword(){
		return $this->objects["password"];
	}

	function setSid($cid_s){
		$this->objects["sid"] = (int)$cid_s;
	}

	function getSid(){
		return $this->objects["sid"];
	}

	function setDevice($device_s){
		if((int)$device_s >= 2)
			throw new \Exception("The value of device is invalid");
		$this->objects["device"] = (int)$device_s;
	}

	function getDevice(){
		return $this->objects["device"];
	}

	function setState($state_s){
		if((int)$state_s >= 10)
			throw new \Exception("The code of state is invalid");
		$this->objects["state"] = $state_s;
	}

	function getState(){
		return $this->objects["state"];
	}

	function setCountin($count_s){
		if((int)$count_s >= 10)
			throw new \Exception("The value of count is invalid");
		$this->objects["countin"] = (int)$count_s;
	}

	function getCountin(){
		return $this->objects["countin"];
	}

	function setCountout($count_s){
		if((int)$count_s >= 10)
			throw new \Exception("The value of count is invalid");
		$this->objects["countout"] = (int)$count_s;
	}

	function getCountout(){
		return $this->objects["countout"];
	}
	
	/**
	 * 为给定的字符串生成一个加"盐"的数列值
	 *
	 * @param string $string 即将被散列的字符串
	 * @param string $salt 从这个串中提取"盐"
	 * @return string 加"盐"之后的散列值
	 */
	private function _getSaltedHash($string,$salt=NULL)
	{
		if($salt==NULL){ // 如果没有传入"盐"，则生成一个"盐"
		    $salt = substr(md5(time()),0,$this->_saltLength);
		}else{ // 如传入了$salt,则从中提取真正的"盐"
		    $salt = substr($salt,0,$this->_saltLength);
		}
		
		// 将"盐"添加到散列值之前并返回散列值
		return $salt.sha1($salt.$string);
	}
}
?>