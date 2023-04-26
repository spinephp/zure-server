<?php
namespace woo\mapper;
require_once("domain/ObjectWatcher.php");

class DomainObjectFactory{
	private $target;
	function __construct($target){
		$this->target = $target;
	}
	
	protected function getFromMap($id){
		return \woo\domain\ObjectWatcher::exists($this->targetClass(),$id);
	}

	protected function addToMap(\woo\domain\DomainObject $obj){
		return \woo\domain\ObjectWatcher::add($obj);
	}

	function createObject(array $array){
		if(isset($array['id'])) {
		$old = $this->getFromMap($array['id']);
		if($old) { return $old;}
		}
		include_once("domain/{$this->target}.php");
		$domain = "\\".$this->targetClass();
		$obj = new $domain($array);
		$this->addToMap($obj);
		return $obj;
	}

    function targetClass(){
	    return "woo\\domain\\".$this->target;
    }
}
?>