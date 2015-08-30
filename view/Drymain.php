<?php
/**
* 对数据表 drymain 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class drymainREST extends REST{
  
	function __construct(){
		parent::__construct("drymain");
	}
	
	function doCreate($item){
		if(empty($item)){
			$drymain['starttime'] = $this->request->getProperty("starttime"); // 取要验证的客户数据
			$drymain['lineno'] = $this->request->getProperty("lineno"); // 取要验证的客户数据
			$drymain['state'] = $this->request->getProperty("state"); // 取要验证的客户数据
			$target["drymain"] = array('fields'=>$drymain);
			$result = $this->changeRecords($target,function($domain,&$result) {
				$session = \woo\base\SessionRegistry::instance();
				$result['id'] = $result['drymain']['id'];
				$session->set('dryid',$result['id']);
 			},true);
			$this->response(json_encode($result),201);
		}	
 	}
	
	function doDelete(){
		$id = $this->request->getProperty("id");
		$target = array(
			"drymain"=>array('fields'=>array('id'),'value'=>$id),
			"drydata"=>array('fields'=>array('mainid','id'),'value'=>$id)
		);
		$this->deleteRecords($target,function($domain,&$result){
		});
	}
}

new drymainREST();

?>