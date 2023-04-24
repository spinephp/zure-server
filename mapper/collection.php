<?php
namespace woo\mapper;
require_once("domain/domain.php");

class Collection implements \Iterator , \woo\domain\Collection{
    protected $dofact;
    protected $total = 0;   //集合元素总数量
    protected $raw = array();   //原始数据
    protected $type = null;

    private $result;
    private $pointer = 0;   //指针
    private $objects = array(); //对象集合

    function __construct(array $raw=null,DomainObjectFactory $dofact=null,$type=null){
		if(is_null($type))
            throw new \Exception("collection is invalid");
		$this->type = $type;
        if(!is_null($raw) && !is_null($dofact)){
            $this->raw = $raw;
            $this->total = count($raw);
        }
        $this->dofact = $dofact;
    }

    function add(\woo\domain\DomainObject $object){ //这里是直接添加对象
        $class = $this->targetClass();
        if(!($object instanceof $class)){
            throw new Exception("this is a {$class} collection");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }

	
    function targetClass(){
        return "\woo\domain\{$this->type}";
    }

    protected function notifyAccess(){
    }

    private function getRow($num){  //获取集合中的单条数据，就是这里通过数据映射器将数据创建成对象
        $this->notifyAccess();
        if($num>=$this->total || $num< 0){
            return null;
        }
        if(isset($this->objects[$num])){
            return $this->objects[$num];
        }
        if(isset($this->raw[$num])){
            $this->objects[$num] = $this->dofact->createobject($this->raw[$num]);
            return $this->objects[$num];
        }
    }

	function Count(){
	    return $this->total;
	}
	
	function elementAt($id){
	    $this->pointer = 0;
		$row = $this->next();
		while($row && ($row->getid()!=$id)){
		    $row = $this->next();
		}
		if($row)
		    return -1;
		else
		    return $this->pointer-1;
	}
	
	function find($id){
	    $this->pointer = 0;
		$row = $this->next();
		while($row && ($row->getid()!=$id)){
		    $row = $this->next();
		}
		return $row;
	}
	
	public function seek($num){
	    $this->pointer += $num;
		if($this->pointer<0)
		    $this->pointer = 0;
		if($this->pointer>$this->total)
		    $this->pointer = $this->total;
		return $this->pointer;
	}
	
    public function rewind(){   //重置指针
        $this->pointer = 0;
    }

    public function current(){  //获取当前指针对象
        return $this->getRow($this->pointer);
    }

    public function first(){  //获取当前指针对象
        return $this->getRow(0);
    }

    public function latest(){  //获取当前指针对象
        return $this->getRow($this->total - 1);
    }

    public function key(){  //获取当前指针
        return $this->pointer;
    }

    public function next(){ //获取当前指针对象,并将指针下移
        $row = $this->getRow($this->pointer);
        if($row){
            $this->pointer++;
        }
        return $row;
    }

    public function valid(){    //验证
        return (!is_null($this->current()));
    }
}
?>
