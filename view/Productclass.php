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
require_once("view/WaterMask.php");

class getProductclassREST extends getREST{
	public function doAny($target){
		/**
		* 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
		* 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
		*
		*/
		$this->pseudoLanguageFields(array("introduction"));
		$this->pseudoFields(array('classname'=>array('name'),'classname_en'=> array('name_en'),'classnames'=> array('name','name_en')));

		return parent::doAny($target);
	}
}

class postProductclassREST extends postREST{
	
	function goodsClassSuccess($Productclass,$finder,&$result){
		$pic = $Productclass->getPicture();
		$token = session_id();
		$sdir = "images/good/$token";
		if(!empty($pic) && $pic!="noimg.png" && file_exists("{$sdir}/$pic")){
			$headshot = sprintf("%d_%d.png",$Productclass->getParentid(),$Productclass->getId());
			rename("{$sdir}/$pic", "images/good/$headshot");
			$Productclass->setPicture($headshot);
			$finder->insert($Productclass);
			$result[0]['picture'] = $headshot;
			self::deldir($sdir);
		}
	}
	
	public function doAny(&$item){
		$item["_processimage"] = "goodsClassSuccess";
		return parent::doAny($item);
	}
}

class putProductclassREST extends putREST{
	
	function goodsClassSuccess($Productclass,$finder,&$result){
		$pic = $Productclass->getPicture();
		$token = session_id();
		$sdir = "images/good/$token";
		if(!empty($pic) && $pic!="noimg.png" && file_exists("{$sdir}/$pic")){
			$headshot = sprintf("%d_%d.png",$Productclass->getParentid(),$Productclass->getId());
			$dimg = "images/good/$headshot";
			if(file_exists($dimg))
				@unlink($dimg);
			rename("{$sdir}/$pic", $dimg);
			$Productclass->setPicture($headshot);
			$finder->insert($Productclass);
			$result[0]['picture'] = $headshot;
			self::deldir($sdir);
		}
	}
	
	public function doAny(&$item){
		$item["_processimage"] = "goodsClassSuccess";
		return parent::doAny($item);
	}
}

class deleteProductclassREST extends deleteREST{
	public function doAny($table,$id){
		$target = array(
			"productclass"=>array('fields'=>array('id','picture'),'value'=>$id)
		);
		return $this->deleteRecords($target,function($domain,&$result) use($target){
			$pic = $domain[0]->getPicture();
			if(!empty($pic) && $pic!='noimg.png' && file_exists("images/good/$pic"))
				unlink("images/good/$pic");
			$result = $result[key($target)];
		});
	}
	
}

new REST('productclass');
?>