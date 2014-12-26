<?php
/**
* 对数据表 product 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class productREST extends REST{
  
	function __construct(){
		parent::__construct("product");
	}
	
	function doGet(){
    /**
     * 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
     * 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
     *
     */
    $filter = $this->request->getProperty("filter");
    $need = null;
    
    // 处理伪字段 size
    if(in_array('size',$filter))
      $need = array('length','width','think','unitlen','unitwid','unitthi','sharp');
      
    // 处理伪字段 longname
    if(in_array('longname',$filter) || in_array('longnames',$filter))
      $need[] = 'classid';
      
    if(!is_null($need))
      $this->request->setProperty('extend',$need);  
      
    parent::doGet();
	}
	
	function doDelete(){
    $id = $this->request->getProperty("id");
    $target = array(
      "product"=>array('fields'=>array('id','picture'),'value'=>$id),
      "producteval"=>array('fields'=>array('proid','id'),'value'=>$id),
      "productcare"=>array('fields'=>array('proid','id'),'value'=>$id),
      "productconsult"=>array('fields'=>array('proid','id'),'value'=>$id),
      "productuse"=>array('fields'=>array('proid','id'),'value'=>$id),
      "cart"=>array('fields'=>array('proid','id'),'value'=>$id),
      "orderproduct"=>array('fields'=>array('proid','id'),'value'=>$id)
    );
    $this->deleteRecords($target,function($domain,&$result){
      $pic = $domain[0]->getPicture();
      if(!empty($pic) && $pic!='noimg.png' && file_exists("images/good/$pic"))
        unlink("images/good/$pic");
    });
	}
}

new productREST();

?>