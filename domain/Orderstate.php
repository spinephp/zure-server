<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Orderstate extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("name","name_en","actor","note","yrrnote","state"));
	}
  
  function getName(){
    return $this->objects["name"];
  }
  
  function setName($name_s){
    $this->objects["name"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
  }
  
  function getName_en(){
    return $this->objects["name_en"];
  }
  
  function setName_en($name_s){
    $this->objects["name_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
  }
  
  function getNames(){
    return array($this->objects["name_en"],$this->objects["name"]);
  }
  
  function getActor(){
    return $this->objects["actor"];
  }
  
  function setActor($actor_s){
    $this->objects["actor"] = htmlentities($actor_s,ENT_QUOTES,'UTF-8');
  }
  
  function getNote(){
    return $this->objects["note"];
  }
  
  function setNote($note_s){
    $this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
  }
  
  function getYrrnote(){
    return $this->objects["yrrnote"];
  }
  
  function setYrrnote($yrrnote_s){
    $this->objects["yrrnote"] = htmlentities($yrrnote_s,ENT_QUOTES,'UTF-8');
  }
  
  function getState(){
    return $this->objects["state"];
  }
  
  function setState($state_s){
    $this->objects["state"] = (int)$state_s;
  }
}
?>