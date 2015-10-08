<?php
namespace woo\domain;
require_once("domain/domain.php");

class ProductEval extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("proid","userid","label","useideas","star","integral","date","useful","status"));
	}

	function setProid($proid_s){
		$this->objects["proid"] = (int)$proid_s;
	}

	function getProid(){
		return $this->objects["proid"];
	}

	function setUserid($userid_s){
		$this->objects["userid"] = (int)$userid_s;
	}

	function getUserid(){
		return $this->objects["userid"];
	}

	function setLabel($label_s){
		$this->objects["label"] = (int)$label_s;
	}

	function getLabel(){
		return $this->objects["label"];
	}

	function setUseideas($useideas_s){
		$this->objects["useideas"] = htmlentities($useideas_s,ENT_QUOTES,'UTF-8');
	}

	function getUseideas(){
		return $this->objects["useideas"];
	}

	function setStar($star_s){
		$this->objects["star"] = (int)$star_s;
	}

	function getStar(){
		return $this->objects["star"];
	}

	function setIntegral($integral_s){
		$this->objects["integral"] = (int)$integral_s;
	}

	function getIntegral(){
		return $this->objects["integral"];
	}

	function setDate($date_s){
		$this->objects["date"] = $this->checkTime($date_s);
	}

	function getDate(){
		return $this->objects["date"];
	}

	function setUseful($useful_s){
		$this->objects["useful"] = (int)$useful_s;
	}

	function getUseful(){
		return $this->objects["useful"];
	}

	function setStatus($status_s){
		if($status_s!='W' && $status_s != 'A' && $status_s != 'S')
			throw new \Exception("The code of status is invalid");
		$this->objects["status"] = $status_s;
	}

	function getStatus(){
		return $this->objects["status"];
	}
	
	function getImages(){
		$oproid = $this->getId()."_";
		$result = array();
		$directory = "images/good/feel/";
		$mydir = dir($directory);
		while($file = $mydir->read())
		{
			if(strpos($file,$oproid)==0)
				$result[] = $file;
		}
		$mydir->close();
		return $result;
	}
  
  /**
   * 该方法通过访问表 orderproduct 来得到 feelid
   * 因客户端访问表 orderproduct 一般都需要用户登录，使用不方便，另外产品评价中使用较频繁，所以特添加此方法
   * @params void
   * @return int feelid
   */
  function getFeelid(){
    require_once("domain/Orderproduct.php");
    $result = 0;
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",Array('id','evalid','feelid'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('evalid')->eq($this->getId());
		$collection = $finder->find($idobj);
    $pro = $collection->current();
    if($pro){
        $result = $pro->getFeelid();
    }
    return $result;
  }
  
  /**
   * 该方法通过访问表 orderproduct 和 order 来得到购买时间
   * 因客户端访问表 orderproduct 和 order 一般都需要用户登录，使用不方便，另外产品评价中使用较频繁，所以特添加此方法
   * @params void
   * @return string (YYYY-MM-DD hh:qq:ss)
   */
  function getBuydate(){
    require_once("domain/Orderproduct.php");
    $result = "";
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",Array('id','orderid','evalid'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('evalid')->eq($this->getId());
		$collection = $finder->find($idobj);
    $pro = $collection->current();
    if($pro){
      require_once("domain/Order.php");
		  $factory = \woo\mapper\PersistenceFactory::getFactory("order",Array('id','time'));
		  $finder = new \woo\mapper\DomainObjectAssembler($factory);
		  $idobj = $factory->getIdentityObject()->field('id')->eq($pro->getOrderid());
		  $collection = $finder->find($idobj);
      $ord = $collection->current();
      if($ord){
        $result = $ord->getTime();
      }
    }
    return $result;
  }
}
?>