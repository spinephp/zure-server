<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("base/SessionRegistry.php");

class Qiye extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","domain","qq","tel","fax","email","techid","busiid","address","address_en","introduction","introduction_en","icp","exchangerate"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getNames(){
		return array($this->getName_en(),$this->getName());
	}

	function getName(){
		return $this->objects["name"];
	}

	function getName_en(){
		return $this->objects["name_en"];
	}

	function setName_en($name_s){
		$this->objects["name_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function setDomain($domain_s){
		if(!filter_var($domain_s, FILTER_VALIDATE_URL))
			throw new \Exception("Url is invalid");
		$this->objects["domain"] = $domain_s;
	}

	function getDomain(){
		return $this->objects["domain"];
	}

	function setQq($qq_s){
		if(!preg_match('/^[1-9][0-9]{4,9}$/', $qq_s))
			throw new \Exception("QQ is invalid");
		$this->objects["qq"] = $qq_s;
	}

	function getQq(){
		return $this->objects["qq"];
	}

	function setTel($tel_s){
		if(!preg_match('/^([\+]?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
			throw new \Exception("Tel is invalid");
		$this->objects["tel"] = $tel_s;
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setFax($fax_s){
		if(!preg_match('/^([\+]?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$fax_s))
			throw new \Exception("Tel is invalid");
		$this->objects["fax"] = $fax_s;
	}

	function getFax(){
		return $this->objects["fax"];
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function setAddress_en($address_s){
		$this->objects["address_en"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddresses(){
		return array($this->getAddress_en(),$this->getAddress());
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function getAddress_en(){
		return html_entity_decode($this->objects["address_en"],ENT_QUOTES,'UTF-8');
	}

	function setEmail($email_s){
		if(!filter_var($email_s, FILTER_VALIDATE_EMAIL))
			throw new \Exception("Email is invalid");
		$this->objects["email"] = $email_s;
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setTechid($techid_s){
		$this->objects["techid"] = (int)$techid_s;
	}

	function getTechid(){
		return $this->objects["techid"];
	}

	function setBusiid($busiid_s){
		$this->objects["busiid"] = (int)$busiid_s;
	}

	function getBusiid(){
		return $this->objects["busiid"];
	}

	function setIntroduction($introduction_s){
		$this->objects["introduction"] = htmlentities($introduction_s,ENT_QUOTES,'UTF-8');
	}

	function getIntroductions(){
		return array($this->getIntroduction_en(),$this->getIntroduction());
	}

	function getIntroduction(){
		return $this->objects["introduction"];
	}

	function getIntroduction_en(){
		return $this->objects["introduction_en"];
	}

	function setIntroduction_en($introduction_s){
		$this->objects["introduction_en"] = htmlentities($introduction_s,ENT_QUOTES,'UTF-8');
	}

	function setIcp($icp_s){
		$this->objects["icp"] = htmlentities($icp_s,ENT_QUOTES,'UTF-8');
	}

	function getIcp(){
		return $this->objects["icp"];
	}

	function setExchangerate($exchangerate_s){
		$this->objects["exchangerate"] = (float)$exchangerate_s;
	}

	function getExchangerate(){
		return $this->objects["exchangerate"];
	}
}
?>