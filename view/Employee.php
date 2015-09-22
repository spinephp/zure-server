<?php
/**
* 对数据表 employee 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("domain/Person.php");
require_once("domain/Systemnotice.php");

class EmployeeREST extends REST{
  
	function __construct(){
		parent::__construct("employee");
	}
	
	function personSuccess($person,$finder,&$result){
		$pic = $person->getPicture();
		if(!empty($pic) && $pic!="noimg.png" && file_exists("images/user/$pic")){
			$imgName = sprintf("u%08d",$person->getId());
			$headshot = $imgName.".png";
			rename("images/user/$pic", "images/user/$headshot");
			$person->setPicture($headshot);
			$finder->insert($person);
			$result['picture'] = $headshot;
		}
	}
	
	function doCreate($item){
		$itemPerson = $item["person"];
		if(empty($itemPerson))
			throw new \woo\base\AppException("none person infomation");
		  
		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$extend['lasttime'] = $now;
		$extend['hash'] = md5($itemPerson['UserName'].$itemPerson['pwd'].$now);
		$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extend,'sucess'=>"personSuccess");
			
		$itemEmployee = $item["employee"];
		if(is_null($itemEmployee))
			throw new \woo\base\AppException("Employee data is null!");
		$extEmployee['userid'] = array('0'=>'id');
		$target["employee"][] = array('fields'=>$itemEmployee,'condition'=>$extEmployee);
		$result = $this->changeRecords($target,function($domain,&$result){
			$result['id'] = $result['employee'][0]['id'];
			$result['employee'][0]['userid'] = $result['person'][0]['id'];
			unset($result['person'][0]['pwd']);
		},true);
		$this->response(json_encode($result),201);
	}
	
	function updateSuccess($person,$finder,&$result){
		$pic = $person->getPicture();
		if(!empty($pic) && $pic!="noimg.png" && file_exists("images/user/$pic")){
			$imgName = sprintf("u%08d",$person->getId());
			$headshot = $imgName.".png";
			rename("images/user/$pic", "images/user/$headshot");
			$person->setPicture($headshot);
			$finder->insert($person);
			$result['picture'] = $headshot;
		}
	}
	/**
	 * 根据 $item 数据，更新 employee 和 person 数据库，并生成回传数据
	 * @param $item - array，键值对数组，包含要更新的数据
	 * @return void
	 */
	function doUpdate($item){
		$itemEmployee = $item["employee"];
		if(is_null($itemEmployee))
			throw new \woo\base\AppException("Employee data is null!");

		$target["employee"][] = array('fields'=>$itemEmployee,'condition'=>$this->request->getProperty("id"));

		$itemPerson = $item["person"];
		if($itemPerson && count($itemPerson)>0){

			$extPerson['id'] = array('0'=>'userid');
			$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extPerson,'sucess'=>"updateSuccess");
		}
		$result = $this->changeRecords($target,function($domain,&$result){
		},false);
		$this->response(json_encode($result),201);
	}
	
	function doDelete(){
		$target["employee"] = array('fields'=>array('id','userid'),'value'=>$this->request->getProperty("id"));
		$target["person"] = array('fields'=>array('id','picture'),'value'=>array('0'=>'userid'));

		$this->deleteRecords($target,function($domain,&$result){
			$pic = $domain[1]->getPicture();
			if(!empty($pic) && $pic!='noimg.png' && file_exists("images/user/$pic"))
				unlink("images/user/$pic");
		});
	}
}

new EmployeeREST();

?>