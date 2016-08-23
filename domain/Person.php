<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("domain/Company.php");
require_once("domain/Custom.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Person extends DomainObject{
	private $custom;
	private $company;

	private $_saltLength = 7;
	
	function __construct($array){
		parent::__construct($array,array("username","pwd","email","active","companyid","name","nick","sex","country","county","address","mobile","tel","qq","identitycard","picture","registertime","lasttime","times","hash"));
		if(isset($array["id"])){
			$id = (int)$array["id"];
			if(!is_null($id)){
				// 载入客户相关数据
				$factory = \woo\mapper\PersistenceFactory::getFactory("custom",array('id','userid'));
				$finder = new \woo\mapper\DomainObjectAssembler($factory);
				$idobj = $factory->getIdentityObject()->field('userid')->eq($id);
				$collection = $finder->find($idobj);
				$this->custom = $collection->current();
			}
		}
	}

	function isCustom(){
	    return !is_null($this->custom);
	}
  
	function getCustom(){
	    return $this->custom;
	}
	
	function setUsername($name_s){
    if (!preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9\-\_\@\.]{3,18}[a-zA-Z0-9]{1}$/', $name_s))
      throw new \Exception("Username is invalid");
		$this->objects["username"] = $name_s;
	}

	function getUsername(){
		return $this->objects["username"];
	}

	function setPwd($password_s){
		$this->objects["pwd"] = (strlen($password_s)!=(40+$this->_saltLength))? $this->_getSaltedHash($password_s):$password_s;
	}

	function getPwd(){
		return $this->objects["pwd"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
		return $this->objects["name"];
	}

	function setCompanyid($companyid_s){
		$this->objects["companyid"] = (int)$companyid_s;
		if($this->objects["companyid"]!=null){
		    $factory = \woo\mapper\PersistenceFactory::getFactory("company",array('id','name'));
		    $finder = new \woo\mapper\DomainObjectAssembler($factory);
		    $idobj = $factory->getIdentityObject()->field('id')->eq($this->objects["companyid"]);
		    $collection = $finder->find($idobj);
			$this->company = $collection->current();
		}
	}

	function getCompanyid(){
		return $this->objects["companyid"];
	}

	function getCompany(){
		return $this->company;
	}
	
	function setQq($qq_s){
    if($qq_s!=null && !preg_match('/^[1-9][0-9]{4,9}$/', $qq_s))
      throw new \Exception("QQ is invalid");
		$this->objects["qq"] = $qq_s;
	}

	function getQq(){
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

	function setMobile($mobile_s){
		if(!preg_match('/^[(86)|0]?(1[3|5|7|8]\d{9})$/',$mobile_s))
			throw new \Exception("Mobile is invalid");
		$this->objects["mobile"] = $mobile_s;
	}

	function getMobile(){
		return $this->objects["mobile"];
	}

	function setEmail($email_s){
		if(!filter_var($email_s, FILTER_VALIDATE_EMAIL))
			throw new \Exception("Email is invalid");
		$this->objects["email"] = $email_s;
	}

	function getEmail(){
		return $this->objects["email"];
	}

	function setActive($active_s){
		if($active_s!='Y' && $active_s != 'N')
			throw new \Exception("The code of active is invalid");
		$this->objects["active"] = $active_s;
	}

	function getActive(){
		return $this->objects["active"];
	}

	function setNick($nick_s){
		$this->objects["nick"] = htmlentities($nick_s,ENT_QUOTES,'UTF-8');
	}

	function getNick(){
		return $this->objects["nick"];
	}

	function setSex($sex_s){
		if($sex_s!='M' && $sex_s != 'F')
			throw new \Exception("Sex is invalid");
		$this->objects["sex"] = $sex_s;
	}

	function getSex(){
		return $this->objects["sex"];
	}

	function setHash($hash_s){
		if(!$this->isHash($hash_s))
			throw new \Exception("Hash is invalid");
		$this->objects["hash"] = $hash_s;
	}

	function getHash(){
		return trim($this->objects["hash"]);
	}

	function setCountry($country_s){
		$this->objects["country"] = htmlentities($country_s,ENT_QUOTES,'UTF-8');
	}

	function getCountry(){
		return $this->objects["country"];
	}

	function setCounty($county_s){
		if(!preg_match('/^\d{6}$/', $county_s))
			throw new \Exception("County is invalid");
		$this->objects["county"] = $county_s;
	}

	function getCounty(){
		return $this->objects["county"];
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setIdentitycard($identitycard_s){
		if(!$this->isIdCard($identitycard_s))
			throw new \Exception("Identitycard is invalid");
		$this->objects["identitycard"] = $identitycard_s;
	}

	function getIdentitycard(){
		return $this->objects["identitycard"];
	}

	function setPicture($picture_s){
		if(!$this->isPicture($picture_s))
			throw new \Exception("picture is invalid");
		$this->objects["picture"] = $picture_s;
	}

	function getPicture(){
		return $this->objects["picture"];
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

	function setTimes($times_s){
		$this->objects["times"] = (int)$times_s;
	}

	function getTimes(){
		return $this->objects["times"];
	}
	
	function isPassword($pwd){
	    $dbpwd = $this->getPwd();
		$tmppwd = $this->_getSaltedHash($pwd,$dbpwd);
		return $tmppwd==$this->getPwd();
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
  
	private function isIdCard($idcard){
      if(empty($idcard)){
          return false;
      }
      $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
      $iSum = 0;
      $idCardLength = strlen($idcard);
      //长度验证
      if(!preg_match('/^\d{17}(\d|x)$/i',$idcard) and!preg_match('/^\d{15}$/i',$idcard))
      {
          return false;
      }
      //地区验证
      if(!array_key_exists(intval(substr($idcard,0,2)),$City))
      {
         return false;
      }
      // 15位身份证验证生日，转换为18位
      if ($idCardLength == 15)
      {
          $sBirthday = '19'.substr($idcard,6,2).'-'.substr($idcard,8,2).'-'.substr($idcard,10,2);
          $d = new DateTime($sBirthday);
          $dd = $d->format('Y-m-d');
          if($sBirthday != $dd)
          {
              return false;
          }
          $idcard = substr($idcard,0,6)."19".substr($idcard,6,9);//15to18
          $Bit18 = getVerifyBit($idcard);//算出第18位校验码
          $idcard = $idcard.$Bit18;
      }
      // 判断是否大于2078年，小于1900年
      $year = substr($idcard,6,4);
      if ($year<1900 || $year>2078 )
      {
          return false;
      }
 
      //18位身份证处理
      $sBirthday = substr($idcard,6,4).'-'.substr($idcard,10,2).'-'.substr($idcard,12,2);
      $d = new \DateTime($sBirthday);
      $dd = $d->format('Y-m-d');
      if($sBirthday != $dd)
       {
          return false;
       }
      //身份证编码规范验证
      $idcard_base = substr($idcard,0,17);
      if(strtoupper(substr($idcard,17,1)) != $this->getVerifyBit($idcard_base))
      {
          return false;
      }else{
          return true;
      }
  }
 
  // 计算身份证校验码，根据国家标准GB 11643-1999
  private function getVerifyBit($idcard_base)
  {
      if(strlen($idcard_base) != 17)
      {
          return false;
      }
      //加权因子
      $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
      //校验码对应值
      $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
      $checksum = 0;
      for ($i = 0; $i < strlen($idcard_base); $i++)
      {
          $checksum += substr($idcard_base, $i, 1) * $factor[$i];
      }
      $mod = $checksum % 11;
      $verify_number = $verify_number_list[$mod];
      return $verify_number;
  }
}
?>