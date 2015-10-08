<?php
/**
* 对数据表 pro_size 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("base/SessionRegistry.php");
require_once("domain/Orderproduct.php");
require_once("domain/Order.php");
require_once("domain/Customgrade.php");
require_once("domain/Custom.php");
require_once("domain/Grade.php");

class productevalREST extends REST{
  
	function __construct(){
		parent::__construct("producteval");
	}
	
	function beforeCreate(&$item){
		$id = $item["orderproductid"];
		$factory = \woo\mapper\PersistenceFactory::getFactory("orderproduct",array("id","orderid"));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->eq($id);
		$collection = $finder->find($idobj);
		$rec = $collection->current();
		if(is_null($rec))
			throw new \woo\base\AppException("Record orderproduct:ID=$id is'n exist!");
		$orderid = $rec->getOrderid();
		
		$factory = \woo\mapper\PersistenceFactory::getFactory("order",array("id","time"));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('id')->eq($orderid);
		$collection = $finder->find($idobj);
		$rec = $collection->current();
		if(is_null($rec))
			throw new \woo\base\AppException("Record order:ID=$orderid is'n exist!");
		$time = $rec->getTime();

		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		
		$d1 = strtotime($time);
		$d2 = strtotime($now);
		if(round($d2-$d1)/3600/24 < 180){
			$session = \woo\base\SessionRegistry::instance();
			$userid = $session->get('userid');
			$factory = \woo\mapper\PersistenceFactory::getFactory("customgrade",array("id","userid","gradeid"));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('userid')->eq($userid);
			$collection = $finder->find($idobj);
			$rec = $collection->current();
			if(is_null($rec))
				throw new \woo\base\AppException("Record customgrade:userid=$userid is'n exist!");
			$gradeid = $rec->getGradeid();
			$factory = \woo\mapper\PersistenceFactory::getFactory("grade",array("id","evalintegral"));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('id')->eq($gradeid);
			$collection = $finder->find($idobj);
			$rec = $collection->current();
			if(is_null($rec))
				throw new \woo\base\AppException("Record grade:ID=$gradeid is'n exist!");
			$integral = $rec->getEvalintegral();
			$item["producteval"]["integral"] = $integral;
			
			$factory = \woo\mapper\PersistenceFactory::getFactory("custom",array("id","userid","integral"));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('userid')->eq($userid);
			$collection = $finder->find($idobj);
			$rec = $collection->current();
			if(is_null($rec))
				throw new \woo\base\AppException("Record custom:userid=$userid is'n exist!");
			$integral += $rec->getIntegral();
			$item["other"][] = array("table"=>"custom","method"=>"put","data"=>array("id"=>$rec->getId(),"integral"=>$integral));
		}
	}
	
	function afterCreate(&$result,$item){
		// 把 images/good/feel/session_id() 目录下的图形文件移动到 images/good/feel 目录下
		// 并删除  images/good/feel/session_id() 目录
		$oproid = $result["orderproduct"]["id"];
		$feeldir = "images/good/feel/";
		$directory = $feeldir.session_id();
		$mydir = dir($directory);
		while($file = $mydir->read())
		{
			if((is_dir("$directory/$file")) || ($file==".") || ($file==".."))
				continue; 
			rename("$directory/$file", "$feeldir{$oproid}_{$file}");
		}
		$mydir->close();
		rmdir($directory);
	}
	
	function doDelete(){
		$id = $this->request->getProperty("id");
		$target = array(
			"producteval"=>array('fields'=>array('id','picture'),'value'=>$id)
		);
		$this->deleteRecords($target,function($domain,&$result) use($target){
			$pic = $domain[0]->getPicture();
			if(!empty($pic) && $pic!='noimg.png' && file_exists("images/good/$pic"))
				unlink("images/good/$pic");
			$result = $result[key($target)];
		});
	}
 }

new productevalREST();

?>