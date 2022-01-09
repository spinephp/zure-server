<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class LevelBuy extends DomainObject{
	
	function __construct($array){
		parent::__construct($array,array("userid","productid","transactionid","origintransid","content","buytime","expirestime","state"));
	}

	function setUserid($data_s){
		$this->objects["userid"] = (int)$data_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setProductid($data_s){
		$this->objects["productid"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getProductid(){
		return $this->objects["productid"];
	}

	function setTransactionid($data_s){
		$this->objects["transactionid"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getTransactionid(){
		return $this->objects["transactionid"];
	}

	function setOrigintransid($data_s){
		$this->objects["origintransid"] = htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getOrigintransid(){
		return $this->objects["origintransid"];
	}

	function setContent($data_s){
		$this->objects["content"] = $data_s; //htmlentities($data_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setExpirestime($data_s){
		$this->objects["expirestime"] = $this->checkTimestampMs($data_s);
	}

	function getExpirestime(){
		return $this->objects["expirestime"];
	}

	function setBuytime($data_s){
		$this->objects["buytime"] = $this->checkTime($data_s);
	}

	function getBuytime(){
		return $this->objects["buytime"];
	}

	function setState($data_s){
		$this->objects["state"] = (int)$data_s;
	}

	function getState(){
		return (int)$this->objects["state"];
	}
}
?>