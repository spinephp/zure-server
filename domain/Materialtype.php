<?php
namespace woo\domain;
require_once("domain/domain.php");

class Materialtype extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("parentid","name","inrightid","outrightid","note"));
	}

	function setParentid($parentid_s){
		$this->objects["parentid"] = (int)$parentid_s;
	}

	function getParentid(){
		return $this->objects["parentid"];
	}

	function setName($name_s){
		$this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
    return $this->objects["name"];
	}

	function setInrightid($inrightid_s){
		$this->objects["inrightid"] = (int)$inrightid_s;
	}

	function getInrightid(){
    return $this->objects["inrightid"];
	}

	function setOutrightid($outrightid_s){
		$this->objects["outrightid"] = (int)$outrightid_s;
	}

	function getOutrightid(){
    	return $this->objects["outrightid"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
    return $this->objects["note"];
	}

	private function getTypeObject($grade){
	    $fields = array_keys($this->getObjects());
	    if(!in_array('id',$fields)) $fields[] = 'id';
	    if(!in_array('parentid',$fields)) $fields[] = 'parentid';
		$factory = \woo\mapper\PersistenceFactory::getFactory("materialtype",fields);
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$obj = $this;
	    $cgrade = (int)log($this->getId(),10);
	    for($i = $cgrade;$i>$grade;$i--){
			  $idobj = $factory->getIdentityObject()->field('id')->eq($obj->getParentid());
			  $collection = $finder->find($idobj);
			  $obj = $collection->current();
	    }
		return $obj;
	}
  
  function getTypename($grade=0){
    $obj = $this->getTypeObject($grade);
    return $obj->getName();
  }
}
?>