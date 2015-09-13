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

class productclassREST extends REST{
  
	function __construct(){
		parent::__construct("productclass");
	}
	
	function doGet(){
		/**
		* 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
		* 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
		*
		*/
		$this->pseudoLanguageFields(array("introduction"));
		$this->pseudoFields(array('classname'=>array('name'),'classname_en'=> array('name_en'),'classnames'=> array('name','name_en')));

		parent::doGet();
	}
	
	function doCreate($item){
    $itemProductclass = $item["productclass"];
		if(is_null($itemProductclass))
			throw new \woo\base\AppException("Productclass data is null!");
        
    // 处理图片水印
    $this->processWatermask($item) ;   
    
    $target["productclass"][] = array('fields'=>$itemProductclass,'sucess'=>function($Productclass,$finder,&$result){
      $pic = $Productclass->getPicture();
      if(!empty($pic) && $pic!="noimg.png" && file_exists("images/good/$pic")){
        $headshot = sprintf("%d_%d.png",$Productclass->getParentid(),$Productclass->getId());
        rename("images/good/$pic", "images/good/$headshot");
        $Productclass->setPicture($headshot);
			  $finder->insert($Productclass);
        $result['picture'] = $headshot;
      }
    });
   
    $result = $this->changeRecords($target,function($domain,&$result){
    },true);
			$this->response(json_encode($result),201);
	}
	
	function doUpdate($item){
    // 处理图片水印
    $this->processWatermask($item) ;   
    parent::doUpdate($item);
	}
	
	function doDelete(){
    $id = $this->request->getProperty("id");
    $target = array(
      "productclass"=>array('fields'=>array('id','picture'),'value'=>$id)
    );
    $this->deleteRecords($target,function($domain,&$result) use($target){
      $pic = $domain[0]->getPicture();
      if(!empty($pic) && $pic!='noimg.png' && file_exists("images/good/$pic"))
        unlink("images/good/$pic");
      $result = $result[key($target)];
    });
	}
  
  private function processWatermask($item){
    if(isset($item['watermask']) && $item['watermask']=='on'){
      $waterText = "";
      $waterImage = "";
      $fontFile = "./arial.ttf";
      $fontSize = 12;
      $textColor = "#CCCCCC";
      $xOffset = 0;
      $yOffset = 0;
      $waterPos = 0;
      $groundImage = "images/good/".$item['productclass']['picture'];
      if($item['watersel']=='0'){
        $waterText = $item['watermasktxt'];
        if(isset( $item['fontfile'])) $fontFile = $item['fontfile'];
        if(isset( $item['fontsize'])) $fontSize = $item['fontsize'];
        if(isset( $item['textcolor'])) $textColor = $item['textcolor'];
        if(isset( $item['xoffset'])) $xOffset = $item['xoffset'];
        if(isset( $item['yoffset'])) $yOffset = $item['yoffset'];
        if(isset( $item['waterpos'])) $waterPos = $item['waterpos'];
      }
      else
        $waterImage = "images/maskimg.png";
      $ret = imageWaterMark($groundImage,$waterPos,$waterImage,$waterText,$fontSize,$textColor, $fontFile,$xOffset,$yOffset);
      if($ret != 0){
        $err = array("Invalid water mask image!","None background image!","Background image size too small!","None font file!","Invalid font color!","Invalid background image!");
				$msg = $err[$ret-1];
        throw new  \woo\base\AppException($msg);
      }
    }
  }
}

new productclassREST();

?>