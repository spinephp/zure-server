<?php
namespace woo\mapper;
require_once("mapper/UpdateFactory.php");

class CustomLoginUpdateFactory extends UpdateFactory{
    function newUpdate(\woo\domain\Domainobject $obj){
        date_default_timezone_set("PRC");   /*��ʱ���������ʱ��,php5Ĭ��Ϊ�������α�׼ʱ��*/
		$id = $obj->getId();
		$cond = null;
		$values['lasttime'] = date("Y-m-d H:i:s",time()); 
		$values['times'] = $obj->getTimes()+1;
		if($id>-1){
			$cond['id'] = $id;
		}
		return $this->buildStatement("person",$values,$cond);
	}
}
?>