<?php
/**
* 数据表 News 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class newsREST extends REST{
	function __construct(){
		parent::__construct("news");
	}
	
	function doGet(){

		$this->pseudoLanguageFields(array("title","content"));

		parent::doGet();
	}
}

new newsREST();
?>