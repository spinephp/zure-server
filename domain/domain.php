<?php
namespace woo\domain;

//require_once("domain/HelperFactory.php");

interface Collection extends \Iterator{
    function add(DomainObject $object);
}

abstract class DomainObject{
    private $id=-1;
	protected $objects = array();

    function __construct($array,$objects){
		$this->initObjects($objects);
		if(is_array($array)){
		  $this->setObjects($array);
		  if(isset($array["id"]))
			$this->id = $array["id"];
		}
		if($this->id==-1){
			//$this->markNew();
		}else{
            //$this->id = $id;
		}
    }

	protected function initObjects($objects){
		if(is_array($objects)){
			foreach($objects as $obj)
				$this->objects[$obj] = "";
		}
	}
  
	function getObjects(){
		return $this->objects;
	}

	function setObjects($obj){
		foreach($obj as $key=>$val){
			$fun = "set".ucfirst($key);
			if(method_exists($this,$fun) && !is_null($val))
				$this->$fun($val);
		}
	}

	function setObjectsFast($obj){
		foreach($obj as $key=>$val){
			if(!is_null($val))
				$this->objects[$key] = $val;
		}
	}
		
    function markNew(){
		ObjectWatcher::addNew($this);
	}

	function markDelete(){
		ObjectWatcher::addDelete($this);
	}

	function markDirty(){
		ObjectWatcher::addDirty($this);
	}

	function markClean(){
		ObjectWatcher::addClean($this);
	}

    function getId(){
        return $this->id;
    }

    function setId($id){
        $this->id = (int)$id;
    }

    function nullId(){
        $this->id = null;
    }

    function finder(){
		return self::getFinder(get_class($this));
	}

	static function getFinder($type){
		return HelperFactory::getFinder($type);
	}

    static function getCollection($type){
        return HelperFactory::getCollection($type);
    }

    function collection(){
        return self::getCollection(get_class($this));
    }
    
    // 检验时间
    function checkTime($time){
		$pos = strpos($time,'.');
		if($pos>0)
			$time = substr($time,0,$pos-1);
		if($time!="0000-00-00 00:00:00")
      if (date('Y-m-d H:i:s', strtotime($time)) != $time && date('Y-m-d', strtotime($time)) != $time)
        throw new \Exception("Invalid date $time");
      return $time;
    }
    
    // 校验图片
    function isPicture($picture){
      return preg_match('/\w+\.(gif|jpe?g|png|GIF|JPE?G|PNG)$/',$picture);
    }
    
    // 校验哈希码
    function isHash($hash){
      return preg_match('/^[a-f\d]{32}$/i',$hash);
    }
}
?>