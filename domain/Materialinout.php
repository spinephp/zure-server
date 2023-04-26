<?php
namespace woo\domain;
require_once("domain/domain.php");

class Materialinout extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("materialid","number","operatorid","authorizerid","operatortime","authorizertime","note"));
	}

	function setMaterialid($materialid_s){
		$this->objects["materialid"] = (int)$materialid_s;
	}

	function getMaterialid(){
		return $this->objects["materialid"];
	}

	function setNumber($number_s){
		$this->objects["number"] = (float)$number_s;
	}

	function getNumber(){
		return $this->objects["number"];
	}

	function setAuthorizerid($authorizerid_s){
		$this->objects["authorizerid"] = (int)$authorizerid_s;
	}

	function getAuthorizerid(){
		return $this->objects["authorizerid"];
	}

	function setOperatorid($operatorid_s){
		$this->objects["operatorid"] = (int)$operatorid_s;
	}

	function getOperatorid(){
		return $this->objects["operatorid"];
	}

	function setOperatortime($operatortime_s){
		$this->objects["operatortime"] = $this->checkTime($operatortime_s);
	}

	function getOperatortime(){
		return $this->objects["operatortime"];
	}

	function setAuthorizertime($authorizertime_s){
		$this->objects["authorizertime"] = $this->checkTime($authorizertime_s);
	}

	function getAuthorizertime(){
		return $this->objects["authorizertime"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}
}
?>