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

class getEmployeeREST extends getREST{
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

class postEmployeeREST extends postREST{
	static $language = 0;
	
	function personSucess($person,$finder,&$result){
		$pic = $person->getPicture();
		$s_dir = "images/user/".session_id();
		$s_file = $s_dir."/".$pic;
		if(!empty($pic) && $pic!="noimg.png" && file_exists($s_file)){
			$imgName = sprintf("u%08d",$person->getId());
			$headshot = $imgName.".png";
			rename($s_file, "images/user/$headshot");
			$person->setPicture($headshot);
			$finder->insert($person);
			$result[0]['picture'] = $headshot;
			self::deldir($s_dir);
		}
	}
	
	public function doAny($item){
		$itemPerson = $item["person"];
		if(empty($itemPerson))
			throw new \woo\base\AppException("none person infomation");
		  
		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$extend['lasttime'] = $now;
		$extend['hash'] = md5($itemPerson['UserName'].$itemPerson['pwd'].$now);
		$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extend,'sucess'=>"personSucess");
			
		$itemEmployee = $item["employee"];
		if(is_null($itemEmployee))
			throw new \woo\base\AppException("Employee data is null!");
		$extEmployee['userid'] = array('0'=>'id');
		$target["employee"][] = array('fields'=>$itemEmployee,'condition'=>$extEmployee);
		return $this->changeRecords($target,function($domain,&$result){
			
			foreach(array("employee","person") as $_target){
				$s = $result[$_target][0];
				unset($result[$_target]);
				$result[$_target][0] = $s;
			}
			$result['id'] = $result['employee'][0]['id'];
			$result['employee'][0]['userid'] = $domain[0]->getId();
			$result["register"] = $register[self::$language];
			$result["email"] = $email[self::$language];
			unset($result['person'][0]['pwd']);
		},true);
	}
}

class putEmployeeREST extends putREST{
	
	function personSuccess($person,$finder,&$result){
		$pic = $person->getPicture();
		$s_dir = "images/user/".session_id();
		$s_file = $s_dir."/".$pic;
		if(!empty($pic) && $pic!="noimg.png" && file_exists($s_file)){
			$headshot = sprintf("u%08d.png",$person->getId());
			$dimg = "images/user/$headshot";
			if(file_exists($dimg))
				@unlink($dimg);
			rename("{$s_dir}/$pic", $dimg);
			$person->setPicture($headshot);
			$finder->insert($person);
			$result[0]['picture'] = $headshot;
			self::deldir($s_dir);
		}
	}
	
	public function doAny($item){
		$itemEmployee = $item["employee"];
		if(is_null($itemEmployee))
			throw new \woo\base\AppException("Employee data is null!");

		$target["employee"][] = array('fields'=>$itemEmployee,'condition'=>$this->request->getProperty("id"));

		$itemPerson = $item["person"];
		if($itemPerson && count($itemPerson)>0){

			$extPerson['id'] = array('0'=>'userid');
			$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extPerson,'sucess'=>"personSuccess");
		}
		return $this->changeRecords($target,function($domain,&$result){
		},false);
	}
}

class deleteEmployeeREST extends deleteREST{
	public function doAny($table,$id){
		$target["employee"] = array('fields'=>array('id','userid'),'value'=>$id);
		$target["person"] = array('fields'=>array('id','picture'),'value'=>array('0'=>'userid'));

		return $this->deleteRecords($target,function($domain,&$result){
			$pic = $domain[1]->getPicture();
			if(!empty($pic) && $pic!='noimg.png' && file_exists("images/user/$pic"))
				unlink("images/user/$pic");
			$result['id'] = $result['employee']['id'];
		});
	}
	
}

new REST('employee');
?>