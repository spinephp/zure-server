<?php
namespace woo\mapper;

class Field{
	protected $name=null;
	protected $operator=null;
	protected $comps=array();
	protected $imcomplete=false;

	// �����ֶ��� ��age
	function __construct($name){
		$this->name = $name;
	}

	// ��Ӳ�������ֵ(���� > 40)��$comps������
	function addTest($operator,$value){
		$this->comps[] = array('name'=>$this->name,'operator'=>$operator,'value'=>$value);
	}

	// comps��һ�����飬��������ж��ַ���������ֶ�
	function getComps(){ return $this->comps; }

	// ���$compsΪ�գ��������бȽ����ݲ��ұ��ֶβ����������ݿ��ѯ
	function isIncomplete(){ return empty($this->comps);}
}
?>