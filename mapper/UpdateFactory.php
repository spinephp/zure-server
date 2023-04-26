<?php
namespace woo\mapper;

class UpdateFactory{
	private $table;
	
	function __construct($table){
		$this->table = $table;
	}
	
    public function newUpdate(\woo\domain\Domainobject $obj){
		$id = $obj->getId();
		$cond = null;
    $values = null;
		foreach($obj->getObjects() as $key=>$val)
		  if($val!=="") $values[$key] = $val;
			
		if($id>-1)
			$cond['id'] = $id;
		
		return $this->buildStatement($this->table,$values,$cond);
	}
	
	protected function buildStatement($table,array $fields,array $conditions=null){
		$terms = array();
		if(!is_null($conditions)){
			$query = "UPDATE {$table} SET ";
			$query .= implode(" = ?,",array_keys($fields))." = ?";
			$terms = array_values($fields);
			$cond = array();
			$query .= " WHERE ";
			foreach($conditions as $key=>$val){
				$cond[] = "$key = ?";
				$terms[] = $val;
			}
			$query .= implode(" AND ",$cond);
		}else{
			$query = "INSERT INTO {$table} (";
			$query .= implode(",",array_keys($fields));
			$query .= ") VALUES (";
			foreach($fields as $name=>$value){
				$terms[] = $value;
				$qs[] = '?';
			}
			$query .= implode(",",$qs);
			$query .= ")";
		}
		return array($query,$terms);
	}
}
?>