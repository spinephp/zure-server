<?php
/**
* 数据表 News 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class navigationREST extends REST{
	function __construct(){
		parent::__construct("navigation");
	}
	
	function doGet(){
    /**
     * 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
     * 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
     *
     */
    $filter = $this->request->getProperty("filter");
    $need = null;
    
    // 处理伪字段 names
    if(in_array('names',$filter)){
      if(!in_array('name',$filter)) $need[] = 'name';
      if(!in_array('name_en',$filter)) $need[] = 'name_en';
    }
      
    if(!is_null($need))
      $this->request->setProperty('extend',$need);  
	
    parent::doGet();
	}
}

new navigationREST();
?>