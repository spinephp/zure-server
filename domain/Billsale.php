<?php
namespace woo\domain;
require_once("domain/domain.php");

class Billsale extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","name","name_en","address","address_en","duty","tel","bank","account"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setName_en($name_s){
		$this->objects["name_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName_en(){
		return $this->objects["name_en"];
	}

	function getNames(){
		return array($this->getName_en(),$this->getName());
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setAddress_en($address_s){
		$this->objects["address_en"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress_en(){
		return $this->objects["address_en"];
	}

	function getAddresss(){
		return array($this->getAddress_en(),$this->getAddress());
	}

	function setDuty($duty_s){
    if(!preg_match('/^[1-9]\d{14}$/',$duty_s))
      throw new \Exception("Duty is error");
		$this->objects["duty"] = $duty_s;
	}

	function getDuty(){
		return $this->objects["duty"];
	}

	function setTel($tel_s){
		if(!preg_match('/^(\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
			throw new \Exception("Tel is error");
		$this->objects["tel"] = $tel_s;
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setBank($bank_s){
		$this->objects["bank"] = htmlentities($bank_s,ENT_QUOTES,'UTF-8');
	}

	function getBank(){
		return $this->objects["bank"];
	}

	function setAccount($account_s){
    if(!preg_match('/^\d{12,19}$/',$account_s))
      throw new \Exception("Account is error");
		$this->objects["account"] = $account_s;
	}

	function getAccount(){
		return $this->objects["account"];
	}
}
?>