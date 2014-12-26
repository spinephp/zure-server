<?php
namespace woo\mapper;
require_once("mapper/SelectionFactory.php");

class HeaderSelectionFactory extends SelectionFactory{
    function __construct(){
	    parent::__construct("qiye");
	}
    function newSelection(IdentityObject $obj){
		$fields = implode(',',$obj->getObjectFields());
		$core = "SELECT $fields FROM qiye";
		list($where,$values) = $this->buildWhere($obj);
		return array($core." ".$where,$values);
	}
}
?>