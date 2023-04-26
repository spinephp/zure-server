<?php
namespace woo\mapper;
require_once("mapper/SelectionFactory.php");

class CustomLoginSelectionFactory extends SelectionFactory{
    function __construct(){
	    parent::__construct("person");
	}
    function newSelection(IdentityObject $obj){
		$fields = implode(',',$obj->getObjectFields());
		$core = "SELECT $fields FROM person";
		list($where,$values) = $this->buildWhere($obj);
		return array($core." ".$where,$values);
	}
}
?>