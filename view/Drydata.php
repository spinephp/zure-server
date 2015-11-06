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