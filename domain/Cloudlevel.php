<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class CloudLevel extends DomainObject{
	private $custom;
	private $company;

	private $_saltLength = 7;
	
	function __construct($array){
		parent::__construct($array,array("email","password","registertime","lasttime","state"));
	}

	function setEmail($email_s){
		if(!filter_var($email_s, FILTER_VALIDATE_EMAIL))
			throw new \Exception("Email is invalid");
		$this->objects["email"] = $email_s;
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setPassword($password_s){
		$this->objects["password"] = (strlen($password_s)!=(40+$this->_saltLength))? $this->_getSaltedHash($password_s):$password_s;
	}

	function getPassword(){
		return $this->objects["password"];
	}

	function setRegistertime($registertime_s){
		$this->objects["registertime"] = $this->checkTime($registertime_s);
	}

	function getRegistertime(){
		return $this->objects["registertime"];
	}

	function setLasttime($lasttime_s){
		$this->objects["lasttime"] = $this->checkTime($lasttime_s);
	}

	function getLasttime(){
		return $this->objects["lasttime"];
	}

	function setState($times_s){
		$this->objects["state"] = (int)$times_s;
	}

	function getState(){
		return (int)$this->objects["state"];
	}
	
	function isPassword($pwd){
	    $dbpwd = $this->getPassword();
		$tmppwd = $this->_getSaltedHash($pwd,$dbpwd);
		return $tmppwd==$this->getPassword();
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