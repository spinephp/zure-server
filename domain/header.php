<?php
namespace woo\domain;
require_once("domain/domain.php");

class header extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","domain","qq","tel","fax","email","techid","busiid","content","content_en","address","icp","navigation","navigationmenu"));
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getNames(){
		return array($this->objects["name"],$this->objects["name_en"]);
	}

	function getName(){
		return $this->objects["name"];
	}

	function setDomain($domain_s){
		if(!filter_var($domain_s, FILTER_VALIDATE_URL))
			throw new \Exception("Url is invalid");
		$this->objects["domain"] = $domain_s;
	}

	function getDomain(){
		return $this->objects["domain"];
	}

	function setQQ($qq_s){
		if(!preg_match('/^[1-9][0-9]{4,9}$/', $qq_s))
			throw new \Exception("QQ is invalid");
		$this->objects["qq"] = $qq_s;
	}

	function getQQ(){
		return $this->objects["qq"];
	}

	function setTel($tel_s){
		if(!preg_match('/^(\+?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$tel_s))
			throw new \Exception("Tel is invalid");
		$this->objects["tel"] = $tel_s;
	}

	function getTel(){
		return $this->objects["tel"];
	}

	function setFax($fax_s){
		if(!preg_match('/^(\+?\d{2,3}[\-\ ]?)?(0?[0-9]{2,3}[\-\ ]?)?\d{7,8}$/',$fax_s))
			throw new \Exception("Tel is invalid");
		$this->objects["fax"]= $fax_s;
	}

	function getFax(){
		return $this->objects["fax"];
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

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setIcp($icp_s){
		$this->objects["icp"] = htmlentities($icp_s,ENT_QUOTES,'UTF-8');
	}

	function getIcp(){
		return $this->objects["icp"];
	}

	function setNavigation($navigation_s){
		$this->objects["navigation"] = htmlentities($navigation_s,ENT_QUOTES,'UTF-8');
	}

	function getNavigation(){
		return $this->objects["navigation"];
	}

	function setNavigationMenu($navigationmenu_s){
		$this->objects["navigationmenu"] = $navigationmenu_s;
	}

	function getNavigationMenu(){
		return $this->objects["navigationmenu"];
	}
}
?>