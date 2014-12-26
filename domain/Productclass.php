<?php
namespace woo\domain;
require_once("domain/domain.php");

class ProductClass extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("parentid","name","name_en","introduction","introduction_en","picture"));
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

	function setName_en($name_s){
		$this->objects["name_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getName(){
    return $this->objects["name"];
	}

	function getName_en(){
		return $this->objects["name_en"];
	}

	function getNames(){
		return array($this->getName_en(),$this->getName());
	}

	function setIntroduction($introduce_s){
		$this->objects["introduction"] = htmlentities($introduce_s,ENT_QUOTES,'UTF-8');
	}

	function setIntroduction_en($introduce_s){
		$this->objects["introduction_en"] = htmlentities($introduce_s,ENT_QUOTES,'UTF-8');
	}

	function getIntroduction_en(){
		return $this->objects["introduction_en"];
	}

	function getIntroduction(){
		return $this->objects["introduction"];
	}

	function getIntroductions(){
		return array($this->getIntroduction_en(),$this->getIntroduction());
	}

	function setPicture($picture_s){
		if(!$this->isPicture($picture_s))
			throw new \Exception("picture is invalid");
		$this->objects["picture"] = $picture_s;
	}

	function getPicture(){
		return $this->objects["picture"];
	}

	private function getClassObject($grade){
    $fields = array_keys($this->getObjects());
    if(!in_array('id',$fields)) $fields[] = 'id';
    if(!in_array('parentid',$fields)) $fields[] = 'parentid';
		$factory = \woo\mapper\PersistenceFactory::getFactory("productclass",fields);
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
  
  function getClassname($grade=0){
    $obj = $this->getClassObject($grade);
    return $obj->getName();
  }
  
  function getClassname_en($grade=0){
    $obj = $this->getClassObject($grade);
    return $obj->getName_en();
  }
  
  function getClassnames($grade=0){
    $obj = $this->getClassObject($grade);
    return array($obj->getName_en(),$obj->getName());
  }
}
?>