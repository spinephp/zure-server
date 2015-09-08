<?php
/**
* 对数据表 drydata 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class drydataREST extends REST{
  
	function __construct(){
		parent::__construct("drydata");
	}
	
	function doCreate($item){
		if(empty($item)){
			$session = \woo\base\SessionRegistry::instance();
			$drydata['time'] = $this->request->getProperty("time"); // 取要验证的客户数据
			$drydata['temperature'] = $this->request->getProperty("temperature"); // 取要验证的客户数据
			$drydata['settingtemperature'] = $this->request->getProperty("settingtemperature"); // 取要验证的客户数据
			$drydata['mode'] = $this->request->getProperty("mode"); // 取要验证的客户数据
			$drydata['mainid'] =$session->get("dryid");
			$target["drydata"] = array('fields'=>$drydata);
			$result = $this->changeRecords($target,function($domain,&$result) {
				$result['id'] = $result['drydata']['id'];
 			},true);
			$this->response(json_encode($result),201);
		}	
 	}
	
	function doDelete(){
		$id = $this->request->getProperty("id");
		$target = array(
			"drydata"=>array('fields'=>array('id'),'value'=>$id),
			"drydata"=>array('fields'=>array('mainid','id'),'value'=>$id)
		);
		$this->deleteRecords($target,function($domain,&$result){
		});
	}
}

new drydataREST();

?>