<?php
namespace woo\mapper;

//字段对象
class Field{
	protected $name=null;	//字段名称
	protected $operator=null;	//操作符
	protected $comps=array();	//存放条件的数组
	protected $imcomplete=false;	//检查条件数组是否有值

	// �����ֶ��� ��age
	function __construct($name){
		$this->name = $name;
	}

	//添加where 条件
	function addTest($operator,$value){
		$this->comps[] = array('name'=>$this->name,'operator'=>$operator,'value'=>$value);
	}

	//获取存放条件的数组
	function getComps(){ return $this->comps; }

	// ���$compsΪ�գ��������бȽ����ݲ��ұ��ֶβ����������ݿ��ѯ
	function isIncomplete(){ return empty($this->comps);}
}
?>