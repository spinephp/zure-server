<?php
namespace woo\domain;
require_once("domain/domain.php");

class OrderProduct extends DomainObject{
	private $parentobject = array();

	function __construct($array){
		parent::__construct($array,array("orderid","proid","price","returnnow","modlcharge","number","moldingnumber","drynumber","firingnumber","packagenumber","evalid","feelid"));
	}

	function setOrderid($orderid_s){
		$this->objects["orderid"] = (int)$orderid_s;
	}

	function getOrderid(){
		return $this->objects["orderid"];
	}

	function setProid($proid_s){
		$this->objects["proid"] = (int)$proid_s;
	}

	function getProid(){
		return $this->objects["proid"];
	}

	function setPrice($price_s){
		$this->objects["price"] = (float)$price_s;
	}

	function getPrice(){
		return $this->objects["price"];
	}

  function setReturnnow($returnnow_s){
    $this->objects["returnnow"] = (float)$returnnow_s;
  }

  function getReturnnow(){
    return $this->objects["returnnow"];
  }

  function setModlcharge($modlcharge_s){
    $this->objects["modlcharge"] = (float)$modlcharge_s;
  }

  function getModlcharge(){
    return $this->objects["modlcharge"];
  }

	function setNumber($number_s){
		$this->objects["number"] = (int)$number_s;
	}

	function getNumber(){
		return $this->objects["number"];
	}

	function setMoldingnumber($number_s){
		$this->objects["moldingnumber"] = (int)$number_s;
	}

	function getMoldingnumber(){
		return $this->objects["moldingnumber"];
	}

	function setDrynumber($number_s){
		$this->objects["drynumber"] = (int)$number_s;
	}

	function getDrynumber(){
		return $this->objects["drynumber"];
	}

	function setFiringnumber($number_s){
		$this->objects["firingnumber"] = (int)$number_s;
	}

	function getFiringnumber(){
		return $this->objects["packagenumber"];
	}

	function setPackagenumber($number_s){
		$this->objects["packagenumber"] = (int)$number_s;
	}

	function getPackagenumber(){
		return $this->objects["packagenumber"];
	}

	function setEvalid($eval_s){
		$this->objects["evalid"] = (int)$eval_s;
	}

	function getEvalid(){
		return $this->objects["evalid"];
	}

	function setFeelid($feel_s){
		$this->objects["feelid"] = (int)$feel_s;
	}

	function getFeelid(){
		return $this->objects["feelid"];
	}
}
?>