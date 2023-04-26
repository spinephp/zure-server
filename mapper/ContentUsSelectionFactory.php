<?php
namespace woo\mapper;
require_once("mapper/SelectionFactory.php");

class ContentUsSelectionFactory extends SelectionFactory{
    function __construct(){
	    parent::__construct("person");
	}
    function newSelection(IdentityObject $obj){
		$fields = implode(',',$obj->getObjectFields());
		$core = "SELECT $fields FROM person";
		list($where,$values) = $this->buildWhere($obj);
		$where = str_replace(" AND "," OR ",$where);
		return array($core." ".$where,$values);
	}
}
?>