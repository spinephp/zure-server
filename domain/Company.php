<?php
namespace woo\domain;
require_once("domain/domain.php");

class Company extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","address","bank","account","email","www","tel","fax","postcard","duty"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setBank($bank_s){
		$this->objects["bank"] = htmlentities($bank_s,ENT_QUOTES,'UTF-8');
	}

	function getBank(){
		return $this->objects["bank"];
	}

	function setAccount($account_s){
    if(!preg_match('/^\d{12,19}$/',$account_s))
      throw new \Exception("Account is invalid");
		$this->objects["account"] = $account_s;
	}

	function getAccount(){
		return $this->objects["account"];
	}

	// function setQQ($qq_s){
		// $this->qq = $qq_s;
	// }

	// function getQQ(){
		// return $this->qq;
	// }

	function setTel($tel_s){
		if(!preg_match('/^(\+?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
		  throw new \Exception("Tel is error");
		$this->objects["tel"] = $tel_s;
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setFax($fax_s){
		if(!preg_match('/^(\+?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$fax_s))
		  throw new \Exception("Tel is error");
		$this->objects["fax"] = $fax_s;
	}

	function getFax(){
		return $this->objects["fax"];
	}

	// function setMobile($mobile_s){
		// $this->mobile = $mobile_s;
	// }

	// function getMobile(){
		// return $this->mobile;
	// }

	function setEmail($email_s){
    if(!filter_var($email_s, FILTER_VALIDATE_EMAIL))
      throw new \Exception("Email is invalid");
		$this->objects["email"] = $email_s;
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setWww($web_s){
    if(!filter_var("http://$web_s", FILTER_VALIDATE_URL))
      throw new \Exception("Url is invalid");
		$this->objects["www"] = $web_s;
	}

	function getWww(){
		return $this->objects["www"];
	}

	function setPostcard($postcard_s){
    if(!preg_match('/^[1-9]\d{5}$/', $postcard_s))
      throw new \Exception("Postcard is invalid");
		$this->objects["postcard"] = $postcard_s;
	}

	function getPostcard(){
		return $this->objects["postcard"];
	}

	function setDuty($duty_s){
		if(!preg_match('/^[1-9]\d{14}$/',$duty_s))
		  throw new \Exception("Duty is error");
		$this->objects["duty"] = $duty_s;
	}

	function getDuty(){
		return $this->objects["duty"];
	}
}
?>