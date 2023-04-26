<?php
namespace woo\domain;
class ObjectWatcher{
	private $all = array();
	private $dirty = array();
	private $new = array();
	private $delete = array();
	private static $instance;

	private function __construct(){}

	static function instance(){
		if(!self::$instance){
			self::$instance = new ObjectWatcher();
		}
		return self::$instance;
	}

	static function globalKey(DomainObject $obj){
		$key = get_class($obj).".".$obj->getId();
		return $key;
	}

	static function add(DomainObject $obj){
		$inst = self::instance();
		$inst->all[$inst->globalKey($obj)] = $obj;
	}

    static function addDelete(DomainObject $obj){
		$self = self::instance();
		$self->delete[$self->globalKey($obj)] = $obj;
	}

	static function addDirty(DomainObject $obj){
		$inst = self::instance();
		if(!in_array($obj,$inst->new,true)){
			$inst->dirty[self::globalKey($obj)] = $obj;
		}
	}

	static function addNew(DomainObject $obj){
		$inst = self::instance();
		// ���ǻ�û��id
		$inst->new[] = $obj;
	}

	static function addClean(DomainObject $obj){
		$self = self::instance();
		unset($self->delete[$self->globalKey($obj)]);
		unset($self->dirty[$self->globalKey($obj)]);
		$self->new = array_filter($self->new,function($a) use ($obj){ return !($a===$obj);});
	}

	function performoperations(){
		foreach($this->dirty as $key=>$obj){
			$obj->finder()->update($obj);
		}
		foreach($this->new as $key=>$obj){
			$obj->finder()->insert($obj);
		}
		$this->dirty = array();
		$this->new = array();
	}

	static function exists($classname,$id){

		$inst = self::instance();
		$key = "$classname.$id";
		if(isset($inst->all[$key])){
		    //throw new \Exception("<$key>");
			return $inst->all[$key];
		}
		return null;
	}
}