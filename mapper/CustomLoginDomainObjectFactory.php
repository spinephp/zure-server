<?php
namespace woo\mapper;

require_once("mapper/DomainObjectFactory.php");
require_once("domain/Person.php");
class CustomLoginDomainObjectFactory extends DomainObjectFactory{
    function __construct(){
	    parent::__construct("CustomLogin");
	}
	function createObject(array $array){
		$old = $this->getFromMap($array['id']);
		if($old) { return $old;}

		$obj = new \woo\domain\Person($array);
		$this->addToMap($obj);
		return $obj;
	}

    function targetClass(){
	    return "woo\\domain\\CustomLogin";
    }
}
?>