<?php
namespace woo\domain;
require_once("domain/domain.php");

class ProductUse extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("proid","userid","title","content","date","status"));
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

	function setTitle($title_s){
		$this->objects["title"] = htmlentities($title_s,ENT_QUOTES,'UTF-8');
	}

	function getTitle(){
		return $this->objects["title"];
	}

	function setContent($content_s){
		$this->objects["content"] = htmlentities($content_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setDate($date_s){
		$this->objects["date"] = $this->checkTime($date_s);
	}

	function getDate(){
		return $this->objects["date"];
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
    $result = array();
    if(file_exists("images/good/feel/g".$this->getId()))
      $result = array_slice(scandir("images/good/feel/g".$this->getId()),2);
    return $result;
  }
  
  /**
   * 该方法通过访问表 orderproduct 和 order 来得到购买时间
   * 因客户端访问表 orderproduct 和 order 一般都需要用户登录，使用不方便，另外产品评价中使用较频繁，所以特添加此方法
   * @params void
   * @return string (YYYY-MM-DD hh:qq:ss)
   */
  function getBuydate(){
    require_once("domain/orderproduct.php");
    $result = "";
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",Array('id','orderid','evalid'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('evalid')->eq($this->getId());
		$collection = $finder->find($idobj);
    $pro = $collection->current();
    if($pro){
      require_once("domain/order.php");
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