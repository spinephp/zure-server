<?php
/**
* 对数据表 department 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("domain/Departmentright.php");
require_once("domain/Systemnotice.php");

class postDepartmentREST extends postREST{
	static $language = 0;
	
	public function doAny($item){
		$itemDepartment = $item["department"][0];
		if(is_null($itemDepartment))
			throw new \woo\base\AppException("Department data is null!");
		$target["department"][] = array('fields'=>$itemDepartment);
			
		return $this->changeRecords($target,function($domain,&$result){
			$_target = "department";
			$s = $result[$_target][0];
			unset($result[$_target]);
			$result[$_target][0] = $s;
			$result['id'] = $result['department'][0]['id'];
		},true);
	}
}

class putDepartmentREST extends putREST{
	public function doAny($item){
		$result = false;
		$result1 = array();
		$itemRight = array();
		$itemRightI = array();
		$target = array();
		$itemDepartment = $item["department"];
		//if(is_null($itemDepartment))
		//	throw new \woo\base\AppException("Department data is null!");

		$itemDepartmentright = $item["departmentright"];
		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$id = $this->request->getProperty("id");
		foreach ($itemDepartmentright as $key => $value) {
			$rId = $itemDepartmentright[$key]['id'];
			if(is_numeric($rId)){
				$itemRight[] = array('fields'=>$value, 'condition'=>array('departmentid' => $id,'time' => $now));
			}else{
				$itemRightI[] = array('fields'=>$value, 'condition'=>array('departmentid'=>$id,'bit'=>substr($rId, 1),'time' => $now));
			}
		}
		if(count($itemDepartment)){
			$target["department"][] = array('fields'=>$itemDepartment,'condition'=>$id);
		}
		if(isset($itemRight)){
			$target["departmentright"] = $itemRight;
		}

		if(isset($itemRightI)){
			$targetI["departmentright"] = $itemRightI;
			$result1 = $this->changeRecords($targetI,function($domain,&$result){
			},true);
		}
		if($itemDepartmentright || $itemDepartment){
			$result = $this->changeRecords($target,function($domain,&$result){
			},false);
			$a = $result["departmentright"];
			$result["departmentright"]=array_merge(isset($a)? $a:array(),$result1["departmentright"]);
			$result['id'] = $id;
		}
		return $result;
	}
}

class deleteDepartmentREST extends deleteREST{
	public function doAny($table,$id){
		$target["department"] = array('fields'=>array('id'),'value'=>$id);
		$target["departmentright"] = array('fields'=>array('departmentid','id'),'value'=>$id);

		return $this->deleteRecords($target,function($domain,&$result){
			$result['id'] = $result['department']['id'];
		});
	}
	
}

new REST('department');
?>