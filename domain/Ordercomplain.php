<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class OrderComplain extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("userid","orderid","content","type","time","status","note"));
	}
  
  function getUserid(){
    return $this->objects["userid"];
  }
  
  function setUserid($userid_s){
    $this->objects["userid"] = (int)$userid_s;
  }
  
  function getOrderid(){
    return $this->objects["orderid"];
  }
  
  function setOrderid($orderid_s){
    $this->objects["orderid"] = (int)$orderid_s;
  }
  
  function getContent(){
    return $this->objects["content"];
  }
  
  function setContent($content_s){
    $this->objects["content"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
  }
  
  function getType(){
    return $this->objects["type"];
  }
  
  function setType($type_s){
    $this->objects["type"] = (int)$type_s;
  }
  
  function getNote(){
    return $this->objects["note"];
  }
  
  function setNote($note_s){
    $this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
  }
  
  function getTime(){
    return $this->objects["time"];
  }
  
  function setTime($time_s){
    $this->objects["time"] = $this->checkTime($time_s);
  }
  
  function getStatus(){
    return $this->objects["status"];
  }
  
  function setStatus($status_s){
    if($status_s!='S' && $status_s != 'D' && $status_s != 'C')
      throw new \Exception("Sex is invalid");
    $this->objects["status"] = $status_s;
  }
}
?>