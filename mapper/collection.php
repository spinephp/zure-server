<?php
namespace woo\mapper;
require_once("domain/domain.php");

class Collection implements \Iterator , \woo\domain\Collection{
    protected $dofact;
    protected $total = 0;
    protected $raw = array();
    protected $type = null;

    private $result;
    private $pointer = 0;
    private $objects = array();

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

    function add(\woo\domain\DomainObject $object){
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

    private function getRow($num){
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
	
    public function rewind(){
        $this->pointer = 0;
    }

    public function current(){
        return $this->getRow($this->pointer);
    }

    public function key(){
        return $this->pointer;
    }

    public function next(){
        $row = $this->getRow($this->pointer);
        if($row){
            $this->pointer++;
        }
        return $row;
    }

    public function valid(){
        return (!is_null($this->current()));
    }
}
?>
