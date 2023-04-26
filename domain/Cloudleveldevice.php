<?php
namespace woo\domain;
require_once("domain/domain.php");

class CloudLevelDevice extends DomainObject{
    private $level;
	function __construct($array){
		parent::__construct($array,array("cloudid","levelid"));
	}

	function getCloudid(){
		return $this->objects["cloudid"];
	}

	function setCloudid($data_s){
		$this->objects["cloudid"] = (int)$data_s;
	}

	function getLevelid(){
		return $this->objects["levelid"];
	}

	function setLevelid($data_s){
		$this->objects["levelid"] = (int)$data_s;
		if($this->objects["levelid"]!=null){
		    $factory = \woo\mapper\PersistenceFactory::getFactory("level",array('id','name'));
		    $finder = new \woo\mapper\DomainObjectAssembler($factory);
		    $idobj = $factory->getIdentityObject()->field('id')->eq($this->objects["levelid"]);
		    $collection = $finder->find($idobj);
			$this->level = $collection->current();
		}
	}

	function getName(){
		return $this->level->getName();
	}
}
?>