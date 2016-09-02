<?php
namespace woo\domain;
require_once("domain/domain.php");

class Employee extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","departmentid","postids","startdate","dateofbirth","myright"));
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setDepartmentid($departmentid_s){
		$this->objects["departmentid"] = (int)$departmentid_s;
	}

	function getDepartmentid(){
		return $this->objects["departmentid"];
	}

	function setPostids($postids_s){
    if (!preg_match('/^([0-9][0-9]){0,10}$/', $postids_s))
      throw new \Exception("Post ids is invalid");
		$this->objects["postids"] = (int)$postids_s;
	}

	function getPostids(){
		return $this->objects["postids"];
	}

	function setStartdate($startdate_s){
		$this->objects["startdate"] = $this->checkTime($startdate_s);
	}

	function getStartdate(){
		return $this->objects["startdate"];
	}

	function setDateofbirth($dateofbirth_s){
		$this->objects["dateofbirth"] = $this->checkTime($dateofbirth_s);
	}

	function getDateofbirth(){
		return $this->objects["dateofbirth"];
	}

	function setMyright($myright_s){
		$this->objects["myright"] = (int)$myright_s;
	}

	function getMyright(){
		return $this->objects["myright"];
	}
}
?>