<?php
/**
* 数据表 News 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class qiyeREST extends REST{
	function __construct(){
		parent::__construct("qiye");
	}
	
	function doGet(){
		/**
		* 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
		* 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
		*
		*/
		$this->pseudoLanguageFields(array("introduction"));
		$this->pseudoFields(array('addresses'=>array('address', 'address_en')));

		parent::doGet();
	}
}

new qiyeREST();
?>