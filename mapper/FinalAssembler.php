<?php
namespace woo\mapper;

require_once("mapper/IdentityObject.php");
require_once("mapper/DomainObjectAssembler.php");

class FinalAssembler{
	protected $tableName = "";
	protected $idobj = "";
	protected $finder = "";
	protected $fields = array();
	function __construct($tableName,$fields,$condictions){
		$this->tableName = $tableName;
		$this->fields = $fields;
		$factory = \woo\mapper\PersistenceFactory::getFactory($tableName,$fields);
		$this->idobj = $this->factory->getIdentityObject();
		$this->finder = new \woo\mapper\DomainObjectAssembler($this->factory);
		foreach($condictions as $cond){
			$fn = $cond["operator"];
			if(method_exists($this->idobj,$fn)){
				$this->idobj = $this->idobj->field($cond["field"])->$fn($cond["value"]);
			}else{
				throw new  \woo\base\AppException("Invalid relation operator ({$fn})!");
			}
		}
    }

    /**
     * 返回记录的数组，如 [{"id":"1","name":"张三"},{"id":‘2“,"name":"李丁"}]
     */
    function find(){
    	$result = array();
		$collection = $this->finder->find($this->idobj);
	    $pro = $collection->next();
	    while($pro){
	    	$res =array();
			foreach($this->fields as $field){
				$fun = "get".ucfirst($field);
				if(method_exists($pro,$fun)){
					$res[$field] = $pro->$fun();
				}else{
					throw new  \woo\base\AppException("Invalid function ({$fun})!");
				}
			}
			$pro = $collection->next();
			array_push($result,$res);
	    }
	    return $result;
    }

    function insert(){
    	$table = "\woo\domain\{ucfirst($this->tableName)}";
		$obj = new $table();
		$this->finder->insert($obj);
    }

    function update(){

    }

    function delete(){
		return $this->finder->delete($this->idobj);
    }
}

?>