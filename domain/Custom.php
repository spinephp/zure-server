<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("domain/Customgrade.php");
require_once("domain/Customaccount.php");

class Custom extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","type","emailstate","mobilestate","accountstate","ip","emailcode","integral"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}
  
  /**
   * 设置用户类型
   * @param $type_s char - 指定用户类型。P - 个人，U - 单位
   */
	function setType($type_s){
		if($type_s!="P" && $type_s!="U")
			throw new \Exception("User's type is invalid");
		$this->objects["type"] = $type_s;
	}

	function getType(){
		return $this->objects["type"];
	}

 
  /**
   * 设置邮件验证状态
   * @param $emailstate_s char - 指定邮件验证状态。Y - 已验证，N - 未验证
   */
	function setEmailstate($emailstate_s){
		if($emailstate_s!="Y" && $emailstate_s!="N")
			throw new \Exception("Email's validate status is invalid");
		$this->objects["emailstate"] = $emailstate_s;
	}

	function getEmailstate(){
		return $this->objects["emailstate"];
	}


  /**
   * 设置手机验证状态
   * @param $mobilestate_s char - 指定手机验证状态。Y - 已验证，N - 未验证
   */
	function setMobilestate($mobilestate_s){
    if($mobilestate_s!="Y" && $mobilestate_s!="N")
      throw new \Exception("Mobile's validate status is invalid");
		$this->objects["mobilestate"] = $mobilestate_s;
	}

	function getMobilestate(){
		return $this->objects["mobilestate"];
	}

	function setAccountstate($accountstate_s){
    if($accountstate_s!="E" && $accountstate_s!="D")
      throw new \Exception("Account's status is invalid");
		$this->objects["accountstate"] = $accountstate_s;
	}

	function getAccountstate(){
		return $this->objects["accountstate"];
	}

	function setIp($ip_s){
    if (!preg_match('/^(([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]).){3}([1-9]?[0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/',$ip_s))
      throw new \Exception("Ip is invalid");
    $this->objects["ip"] = $ip_s;
	}

	function getIp(){
		return $this->objects["ip"];
	}

	function setEmailcode($emailcode_s){
		if(!$this->isHash($emailcode_s))
			throw new \Exception("emailcode is invalid");
    $this->objects["emailcode"] = $emailcode_s;
	}

	function getEmailcode(){
		return $this->objects["emailcode"];
	}

	function setIntegral($integral_s){
    $this->objects["integral"] = (int)$integral_s;
	}

	function getIntegral(){
		return $this->objects["integral"];
	}
	
	function getPerson(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Person");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->eq($this->getUserid());
		$collection = $finder->find($idobj);
		return $collection;
	}
	
	function getAccount(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\CustomAccount");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('userid')->eq($this->getUserid());
		$collection = $finder->find($idobj);
		return $collection;
	}
	
	function getGrade(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\CustomGrade");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('userid')->eq($this->getUserid());
		$collection = $finder->find($idobj);
		return $collection->current();
	}
}
?>