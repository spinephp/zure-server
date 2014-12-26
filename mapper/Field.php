<?php
namespace woo\mapper;

class Field{
	protected $name=null;
	protected $operator=null;
	protected $comps=array();
	protected $imcomplete=false;

	// 设置字段名 如age
	function __construct($name){
		$this->name = $name;
	}

	// 添加操作符和值(例如 > 40)到$comps属性中
	function addTest($operator,$value){
		$this->comps[] = array('name'=>$this->name,'operator'=>$operator,'value'=>$value);
	}

	// comps是一个数组，因此我们有多种方法来检查字段
	function getComps(){ return $this->comps; }

	// 如果$comps为空，则我们有比较数据并且本字段不能用于数据库查询
	function isIncomplete(){ return empty($this->comps);}
}
?>