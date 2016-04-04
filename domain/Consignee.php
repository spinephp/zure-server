<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Consignee extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","name","country","province","city","zone","address","tel","email","mobile","postcard"));
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

	function setCountry($country_s){
		  $this->objects["country"] = (int)$country_s;
	}

	function getCountry(){
		return $this->objects["country"];
	}

	function setProvince($province_s){
    if(preg_match('/^[1-9]\d{1}$/',$province_s))
		  $this->objects["province"] = $province_s;
    else
      throw new \Exception("Province code is error");
	}

	function getProvince(){
		return $this->objects["province"];
	}

	function setCity($city_s){
    if(preg_match('/^\d{2}$/',$city_s))
  		$this->objects["city"] = $city_s;
    else
      throw new \Exception("City code is error");
	}

	function getCity(){
		return $this->objects["city"];
	}

	function setZone($zone_s){
    if(preg_match('/^\d{2}$/',$zone_s))
		  $this->objects["zone"] = $zone_s;
    else
      throw new \Exception("Zone code is error");
	}

	function getZone(){
		return $this->objects["zone"];
	}

	function setTel($tel_s){
    if(preg_match('/^(\+?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
		  $this->objects["tel"] = $tel_s;
    else
      throw new \Exception("Tel is error");
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setMobile($mobile_s){
    if(!preg_match('/^[(86)|0]?(1[3|5|8]\d{9})|\s{0}$/',$mobile_s))
      throw new \Exception("Mobile is error");
		$this->objects["mobile"] = $mobile_s;
	}

	function getMobile(){
		return $this->objects["mobile"];
	}

	function setEmail($email_s){
    if(!preg_match('/^[a-z0-9]([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/',$email_s))
		//if(!filter_var ($email_s, FILTER_VALIDATE_EMAIL))
      throw new \Exception("email is error");
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

	function setPostcard($postcard_s){
    if(!empty($postcard_s)&&!preg_match('/^[1-9]\d{5}$/',$postcard_s))
      throw new \Exception("postcard is error");
		$this->objects["postcard"] = $postcard_s;
	}

	function getPostcard(){
		return $this->objects["postcard"];
	}
}
?>