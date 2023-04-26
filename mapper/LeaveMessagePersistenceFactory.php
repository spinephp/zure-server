<?php
namespace woo\mapper;

require_once("mapper/PersistenceFactory.php");
require_once("mapper/SelectionFactory.php");
require_once("mapper/UpdateFactory.php");
require_once("mapper/Collection.php");
require_once("mapper/DomainObjectFactory.php");

class LeaveMessageIdentityObject extends IdentityObject{
    function __construct($field=null){
	    parent::__construct($field,array('id','name','email','tel','company','address'));
	}
}

class LeaveMessagePersistenceFactory extends PersistenceFactory{
    function getCollection(array $raw){
	    return new Collection($raw,$this->getDomainObjectFactory(),"LeaveMessage");
	}
	
    function getDomainObjectFactory(){
	    $dofect = new DomainObjectFactory("LeaveMessage");
		return $dofect;
	}
	
	function getSelectionFactory(){
	    return new SelectionFactory("liuyan");
	}
	
	function getUpdateFactory(){
	    return new UpdateFactory("liuyan");
	}
	
	function getDeletionFactory(){
	    return new SelectionFactory("liuyan","DELETE");
	}
	
	function getIdentityObject(){
	    return new LeaveMessageIdentityObject();
	}
}
?>