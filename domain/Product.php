<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("domain/Productclass.php");
require_once("domain/Producteval.php");
require_once("domain/Productconsult.php");
require_once("domain/Person.php");

class Product extends DomainObject{
	private $parentobject = array();

	function __construct($array){
		parent::__construct($array,array("classid","length","width","think","unitlen","unitwid","unitthi","picture","sharp","unit","weight","homeshow","price","returnnow","evalintetral","feelintegral","amount","cansale","status","physicoindex","chemicalindex","note"));
	}
  
  function getParent($obj){
		$this->parentobject = $obj;
	}

	function setClassid($classid_s){
	    if(!is_null($classid_s)){
		    $this->objects["classid"] = (int)$classid_s;
		    $this->parentobject = new ProductClass($classid_s);
		}
	}

	function getClassid(){
		return $this->objects["classid"];
	}

	function getClassObject($grade){
		$factory = \woo\mapper\PersistenceFactory::getFactory("productclass",array("id","parentid","name","name_en","introduction","introduction_en","picture"));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$parent = $this->getClassid();
		$i = 3;
		do{
		  $idobj = $factory->getIdentityObject()->field('id')->eq($parent);
		  $collection = $finder->find($idobj);
		  $obj = $collection->current();
      if(!empty($obj))
  			$parent = $obj->getParentid();
			$i--;
		}while($i>$grade && isset($parent) && $parent>=0);
		return $obj;
	}

	function getEval(){
			$j = 0;
      $result = array();
			foreach( $this->getEvalCollection() as $eval ){
				$factory = \woo\mapper\PersistenceFactory::getFactory("Person",array('id','name','country'));
				$finder = new \woo\mapper\DomainObjectAssembler($factory);
				$idobj = $factory->getIdentityObject()->field('id')->eq($eval->getUserid());
				$collection = $finder->find($idobj);
				if($collection){
				  $obj = $collection->current();
				  $result[$j]["name"] = $obj->getUsername();
				  $result[$j]["country"] = $obj->getCountry();
				  $result[$j]["grade"] = $obj->getCustom()->getGrade()->getGrade()->getImage();
				  $result[$j]["gradename"] = $obj->getCustom()->getGrade()->getGrade()->getName();
				
				  $result[$j]["star"] = $eval->getStar();
				  $result[$j]["title"] = $eval->getTitle();
				  $result[$j]["merit"] = $eval->getMerit();
				  $result[$j]["demerit"] = $eval->getDemerit();
				  $result[$j]["review"] = $eval->getUseideas();
				  $result[$j]["date"] = $eval->getDate();
				  $j++;
				}
			}
      return $result;
  }
  
	function getEvalCollection(){
		$factory = \woo\mapper\PersistenceFactory::getFactory("ProductEval",array('id','proid','userid','label','useideas','star','date','useful','status'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('proid')->eq($this->getid());
		$collection = $finder->find($idobj);
		return $collection;
	}
	
	function get_ClassName($grade=2,$fmt="C"){
		$obj = $this->getClassObject($grade);
		return empty($obj)? null:$obj->getName($fmt);
	}
	
	function getName(){
    return $this->get_ClassName(2,"C");
	}
	
	function getName_en(){
    return $this->get_ClassName(2,"E");
	}
	
	function getNames(){
    return array($this->get_ClassName(2,"E"),$this->get_ClassName(2,"C"));
	}
	
  /**
   * 取产品中文命名
   */
  function getLongname(){
    return $this->_getClassName("C").$this->get_ClassName(2,"C");
  }
  
  function getLongnames(){
    return array($this->_getClassName("E").$this->get_ClassName(2,"E"),$this->_getClassName("C").$this->get_ClassName(2,"C"));
  }
  
	function getClassname(){
    return $this->_getClassName('C');
  }
  
	function getClassnames(){
    return array($this->_getClassName('E'),$this->_getClassName('C'));
  }
  
	function _getClassName($fmt="C")
	{
		$name0 = $this->get_ClassName(0,$fmt);
		$name1 = $this->get_ClassName(2,$fmt);
		switch($fmt){
			case 'C': $name = str_replace("制品","",$name0);break;
			case 'c': $name = str_replace("结合碳化硅制品",$name1,$name0);break;
			case 'E': $name = str_replace("Products","",$name0);break;
			case 'e': $name = str_replace("bonded SiC Products",$name1,$name0);break;
		}
		return $name;
	}

	function getIntroduction($fmt='C')
	{
		// $factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\ProductClass");
		// $finder = new \woo\mapper\DomainObjectAssembler($factory);
		// $idobj = $factory->getIdentityObject()->field('id')->eq($this->getClassId());
		// $collection = $finder->find($idobj);
		$obj = $this->getClassObject(2);//$collection->current();
		return $obj->getIntroduction($fmt);
	}
		
	function setLength($length_s){
		$this->objects["length"] = (float)$length_s;
	}

	function getLength(){
		return $this->objects["length"];
	}

	function setWidth($width_s){
		$this->objects["width"] = (float)$width_s;
	}

	function getWidth(){
		return $this->objects["width"];
	}

	function setThink($think_s){
		$this->objects["think"] = (float)$think_s;
	}

	function getThink(){
		return $this->objects["think"];
	}

	function setUnitlen($unit_s){
    if(!preg_match('/^mm|\"$/',$unit_s))
      throw new \Exception("Unit is error");
		$this->objects["unitlen"] = $unit_s;
	}

	function getUnitlen(){
		return $this->objects["unitlen"];
	}

	function setUnitwid($unit_s){
    if(!preg_match('/^mm|\"$/',$unit_s))
      throw new \Exception("Unit is error");
		$this->objects["unitwid"] = $unit_s;
	}

	function getUnitwid(){
		return $this->objects["unitwid"];
	}

	function setUnitthi($unit_s){
    if(!preg_match('/^(mm|\")$/',$unit_s))
      throw new \Exception("Unit is error");
		$this->objects["unitthi"] = $unit_s;
	}

	function getUnitthi(){
		return $this->objects["unitthi"];
	}
	
	function setPicture($picture_s){
    if(!$this->isPicture($picture_s))
      throw new \Exception("picture is invalid");
		$this->objects["picture"] = $picture_s;
	}

	function getPicture(){
		return $this->objects["picture"];
	}

	function setSharp($sharp_s){
		$this->objects["sharp"] = (int)$sharp_s;
	}

	function getSharp(){
		return $this->objects["sharp"];
	}

	function setUnit($unit_s){
    if(!preg_match('/^(片|块|只|根|吨)$/',$unit_s))
      throw new \Exception("Unit is error");
		$this->objects["unit"] = $unit_s;
	}

	function getUnit(){
		return $this->objects["unit"];
	}

	function setWeight($weight_s){
		$this->objects["weight"] = (float)$weight_s;
	}

	function getWeight(){
		return $this->objects["weight"];
	}

	function setHomeshow($homeshow_s){
    if($homeshow_s!='Y' && $homeshow_s!='N')
      throw new \Exception("Homeshow is error");
		$this->objects["homeshow"] = $homeshow_s;
	}

	function getHomeshow(){
		return $this->objects["homeshow"];
	}

	function setPrice($price_s){
		$this->objects["price"] = (float)$price_s;
	}

	function getPrice(){
		return $this->objects["price"];
	}

  function setReturnnow($returnnow_s){
    $this->objects["returnnow"] = (float)$returnnow_s;
  }

  function getReturnnow(){
    return $this->objects["returnnow"];
  }

  function setEvalintegral($evalintegral_s){
    $this->objects["evalintegral"] = (int)$evalintegral_s;
  }

  function getEvalintegral(){
    return $this->objects["evalintegral"];
  }

  function setFeelintegral($feelintegral_s){
    $this->objects["feelintegral"] = (int)$feelintegral_s;
  }

  function getFeelintegral(){
    return $this->objects["feelintegral"];
  }

	function setAmount($amount_s){
		$this->objects["amount"] = (int)$amount_s;
	}

	function getAmount(){
		return $this->objects["amount"];
	}

	function setCansale($cansale_s){
    if($cansale_s!='Y' && $cansale_s!='N')
      throw new \Exception("Cansale value is error");
		$this->objects["cansale"] = $cansale_s;
	}

	function getCansale(){
		return $this->objects["cansale"];
	}

	function setStatus($status_s){
    if($status_s!='O' && $status_s!='D' && $status_s!='P' && $status_s!='N')
      throw new \Exception("Status value is error");
		$this->objects["status"] = $status_s;
	}

	function getStatus(){
		return $this->objects["status"];
	}

	function setPhysicoindex($physicoindex_s){
		$this->objects["physicoindex"] = (int)$physicoindex_s;
	}

	function getPhysicoindex(){
		return $this->objects["physicoindex"];
	}

	function setChemicalindex($chemicalindex_s){
		$this->objects["chemicalindex"] = (int)$chemicalindex_s;
	}

	function getChemicalindex(){
		return $this->objects["chemicalindex"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}
	
  function getSize(){
        $len = $this->getLength().$this->getUnitlen();
        $wid = $this->getWidth().$this->getUnitwid();
		$thi = $this->getThink().$this->getUnitthi();
		$h = $this->getNote().$this->getUnitlen();
        switch($this->getSharp()){
            case 1: // 立方体
                $sharp = $len."×".$wid."×".$thi;
                break;
			case 2: // 半圆柱 
				$sharp = "R".$len."×".$thi; 
				break;
			case 3: // 圆柱 
				$sharp = "D".$len."×".$thi; 
				break;
			case 4: // 圆管 
				$sharp = "D".$len."×d".$wid."×".$thi; 
				break;
			case 5: // 方管 
				$sharp = $len."×".$wid."×".$h."x".$thi; 
				break;
			case 6: // 方台 
				$sharp = $len."×".$wid."×".$thi; 
				break;
			case 7: // 圆台 
				$sharp = "D".$len."×d".$wid."×".$thi; 
				break;
			case 8: // 特异型 
				$sharp = $this->getNote(); 
				break;
            default:
                $sharp = $len."×".$wid."×".$thi;
        }
        return $sharp;
	}
}
?>
