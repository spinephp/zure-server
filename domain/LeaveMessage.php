<?php
namespace woo\domain;
require_once("domain/domain.php");

class LeaveMessage extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","company","address","title","email","tel","content","time","ip"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setTitle($title_s){
		$this->objects["title"] = htmlentities($title_s,ENT_QUOTES,'UTF-8');
	}

	function getTitle(){
		return $this->objects["title"];
	}

	function setCompany($company_s){
		$this->objects["company"] = htmlentities($company_s,ENT_QUOTES,'UTF-8');
	}

	function getCompany(){
		return $this->objects["company"];
	}

	function setTel($tel_s){
		if(!preg_match('/^(\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
			throw new \Exception("Tel is invalid");
		$this->objects["tel"] = $tel_s;
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setEmail($email_s){
		if(!filter_var($email_s, FILTER_VALIDATE_EMAIL))
			throw new \Exception("Email is invalid");
		$this->objects["email"] = $email_s;
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setContent($content_s){
		$this->objects["content"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setIp($ip_s){
		if (!preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip_s))
			throw new \Exception("Ip is invalid");
		$this->objects["ip"] = $ip_s;
	}

	function getIp(){
		return $this->objects["ip"];
	}
}
?>