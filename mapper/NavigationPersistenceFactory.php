<?php
namespace woo\mapper;

require_once("mapper/PersistenceFactory.php");
require_once("mapper/SelectionFactory.php");
require_once("mapper/UpdateFactory.php");
require_once("mapper/Collection.php");
require_once("mapper/DomainObjectFactory.php");

class NavigationIdentityObject extends IdentityObject{
    function __construct($field=null){
	    parent::__construct($field,array('id','name','name_en','command'));
	}
}

class NavigationPersistenceFactory extends PersistenceFactory{
    function getCollection(array $raw){
	    return new Collection($raw,$this->getDomainObjectFactory(),"Navigation");
	}
	
    function getDomainObjectFactory(){
	    $dofect = new DomainObjectFactory("Navigation");
		return $dofect;
	}
	
	function getSelectionFactory(){
	    return new SelectionFactory("daohang");
	}
	
	function getUpdateFactory(){
	    return new UpdateFactory("daohang");
	}
	
	function getDeletionFactory(){
	    return new SelectionFactory("daohang","DELETE");
	}
	
	function getIdentityObject(){
	    return new NavigationIdentityObject();
	}
}
?>