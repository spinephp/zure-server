<?php
namespace woo\domain;
require_once("domain/domain.php");

class Carriageclass extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("address","chargeid"));
	}

	function setAddress($address_s){
		$this->objects["address"] = htmlentities($address_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setChargeid($chargeid_s){
		$this->objects["chargeid"] = (int)$chargeid_s;
	}

	function getChargeid(){
		return $this->objects["chargeid"];
	}
  
  function getCharge($weight){
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\CarriageCharge");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->eq($this->getChargeid());
		$collection = $finder->find($idobj);
		$charge = $collection->current();
    return $charge->getCharge($weight);
  }
}
?>