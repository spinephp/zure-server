<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("domain/Grade.php");

class CustomGrade extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","gradeid","date"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setGradeid($gradeid_s){
		$this->objects["gradeid"] = (int)$gradeid_s;
	}

	function getGradeid(){
		return $this->objects["gradeid"];
	}

	function setDate($date_s){
		$this->objects["date"] = $this->checkTime($date_s);
	}

	function getDate(){
		return $this->objects["date"];
	}
	
	function getGrade(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Grade");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->eq($this->getGradeid());
		$collection = $finder->find($idobj);
		return $collection->current();
	}
}
?>