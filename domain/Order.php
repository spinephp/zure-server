<?php
namespace woo\domain;
require_once("domain/domain.php");
require_once("domain/Orderproduct.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");

class Order extends DomainObject{

	private $custom;
	private $company;

	function __construct($array){
		parent::__construct($array,array("code","userid","shipdate","consigneeid","paymentid","transportid","billtypeid","billid","billcontentid","downpayment","guarantee","guaranteeperiod","carriagecharge","returnnow","time","note","stateid","contract"));
	}

	function setCode($code_s){
		$this->objects["code"] = (int)$code_s;
	}

	function getCode(){
		return $this->objects["code"];
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setShipdate($shipdate_s){
		$this->objects["shipdate"] = (int)$shipdate_s;
	}

	function getShipdate(){
		return $this->objects["shipdate"];
	}

	function setConsigneeid($consigneeid_s){
		$this->objects["consigneeid"] = (int)$consigneeid_s;
	}

	function getConsigneeid(){
		return $this->objects["consigneeid"];
	}

	function setTransportid($transportid_s){
		$this->objects["transportid"] = (int)$transportid_s;
	}

	function getTransportid(){
		return $this->objects["transportid"];
	}
	
	function setDownpayment($downpayment_s){
		$this->objects["downpayment"] = (int)$downpayment_s;
	}

	function getDownpayment(){
		return $this->objects["downpayment"];
	}

	function setPaymentid($paymentid_s){
		$this->objects["paymentid"] = (int)$paymentid_s;
	}

	function getPaymentid(){
		return $this->objects["paymentid"];
	}

	function setGuarantee($guarantee_s){
		$this->objects["guarantee"] = (int)$guarantee_s;
	}

	function getGuarantee(){
		return $this->objects["guarantee"];
	}

	function setGuaranteeperiod($guaranteeperiod_s){
		$this->objects["guaranteeperiod"] = (int)$guaranteeperiod_s;
	}

	function getGuaranteeperiod(){
		return $this->objects["guaranteeperiod"];
	}

	function setBillid($billid_s){
		$this->objects["billid"] = (int)$billid_s;
	}

	function getBillid(){
		return $this->objects["billid"];
	}

	function setBilltypeid($billtypeid_s){
		$this->objects["billtypeid"] = (int)$billtypeid_s;
	}

	function getBilltypeid(){
		return $this->objects["billtypeid"];
	}

	function setBillcontentid($billcontentid_s){
		$this->objects["billcontentid"] = (int)$billcontentid_s;
	}

	function getBillcontentid(){
		return $this->objects["billcontentid"];
	}

  function setCarriagecharge($carriagecharge_s){
    $this->objects["carriagecharge"] = (float)$carriagecharge_s;
  }

  function getCarriagecharge(){
    return $this->objects["carriagecharge"];
  }

  function setReturnnow($returnnow_s){
    $this->objects["returnnow"] = (float)$returnnow_s;
  }

  function getReturnnow(){
    return $this->objects["returnnow"];
  }
  
	function setTime($time_s){
		$this->objects["time"] = $this->checkTime($time_s);
	}

	function getTime(){
		return $this->objects["time"];
	}

	function setNote($note_s){
		$this->objects["note"] = htmlentities($note_s,ENT_QUOTES,'UTF-8');
	}

	function getNote(){
		return $this->objects["note"];
	}

	function setStateid($stateid_s){
		$this->objects["stateid"] = (int)$stateid_s;
	}

	function getStateid(){
		return $this->objects["stateid"];
	}

	function setContract($contract_s){
		$this->objects["contract"] = (int)$contract_s;
	}

	function getContract(){
		return $this->objects["contract"];
	}
  
  function getProducts(Array $fields){
    $i = 0;
    $result = Array();
    $legal = $fields;
    if(empty($legal)){
      $legal = array('id','orderid','proid','number','price','returnnow','modlcharge',"moldingnumber","drynumber","firingnumber","packagenumber","evalid","feelid");
    }else if(!in_array('orderid',$legal))
      $legal[] = 'orderid';
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",$legal);
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('orderid')->eq($this->getId());
		$collection = $finder->find($idobj);
    $pro = $collection->next();
    while($pro){
      foreach($fields as $field){
        $fun = "get".ucfirst($field);
        $result[$i][$field] = $pro->$fun();
      }
      $pro = $collection->next();
      $i++;
    }
    return $result;
  }
}
?>