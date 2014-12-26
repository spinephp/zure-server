<?php
namespace woo\mapper;

require_once("mapper/IdentityObject.php");
require_once("mapper/collection.php");
require_once("mapper/DomainObjectFactory.php");
require_once("mapper/UpdateFactory.php");

class PersistenceFactory{
  private $target;
  private $fields;
  function __construct($target,array $fields){
		$this->target = "`$target`";
		$this->domain = ucfirst($target);
		$this->fields = $fields;
	}
  
    function getCollection(array $raw){
	    return new Collection($raw,$this->getDomainObjectFactory(),$this->domain);
	}
	
    function getDomainObjectFactory(){
	    $dofect = new DomainObjectFactory($this->domain);
		return $dofect;
	}
	
	function getSelectionFactory(){
	    return new SelectionFactory($this->target);
	}
	
	function getUpdateFactory(){
	    return new UpdateFactory($this->target);
	}
	
	function getDeletionFactory(){
	    return new SelectionFactory($this->target,"DELETE");
	}
	
	function getIdentityObject(){
	    return new IdentityObject(null,$this->fields);
	}
	
	static function getFactory($typestr,array $fields)
	{
    return new self($typestr,$fields);
	}
	
	static function getFinder($typestr,array $fields){
	    $factory = self::getFactory($typestr,$fields);
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		return $finder;
	}
	
};
?>