<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Ordersstate extends DomainObject{
   
   private $orderstate = null;

	function __construct($array){
		parent::__construct($array,array("orderid","time","stateid"));
	}

	function setOrderid($orderid_s){
		$this->objects["orderid"] = (int)$orderid_s;
	}

	function getOrderid(){
		return $this->objects["orderid"];
	}

  function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setStateid($stateid_s){
		$this->objects["stateid"] = (int)$stateid_s;
	}

	function getStateid(){
		return $this->objects["stateid"];
	}
  
  private function getState(){
    if($this->orderstate==null){
			$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\OrderState");
		  $finder = new \woo\mapper\DomainObjectAssembler($factory);
		  $idobj = $factory->getIdentityObject()->field('id')->eq($this->getStateid());
		  $collection = $finder->find($idobj);
      $this->orderstate = $collection->current();
    }
    return $this->orderstate;
  }
  
  function getName(){
    return $this->getState()->getName();
  }
  
  function getActor(){
    return $this->getState()->getActor();
  }
}
?>