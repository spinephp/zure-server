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

class postCompanyREST extends postREST{
	public function doAny($item){
		$company = new \woo\domain\Company(null);
		while(list($key,$val)=each($item)){
			if($key!="id"){
				$name = "set".ucfirst($key);
				if(method_exists($company,$name) && !is_null($val))
					$company->$name($val);
				else
					throw new  \woo\base\AppException("Invalid field name or field value!");
			}
		}
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Company");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$finder->insert($company);
		$item['id'] = $company->getId();
		return $item;
	}
}

class putCompanyREST extends putREST{
	public function doAny($item){
		// 生成 company 目标
		$company = new \woo\domain\Company(null);
		foreach($item as $key=>$val){
			$fun = "set".ucfirst($key);
			if(!method_exists($company,$fun))
				throw new  \woo\base\AppException("Invalid field name $key or field value!");
			if(!is_null($val))
				$company->$fun($val);
		}

		// 把订单数据插入表 company 中
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Company");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$finder->insert($company);
		$item['id'] = $company->getId();
		return $item;
	}
}
new REST('company');

?>