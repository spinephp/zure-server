<?php
namespace woo\mapper;

require_once("mapper/IdentityObject.php");
class SelectionFactory{
	private $table;
    function __construct($table,$option="SELECT"){
	    $this->table = $table;
      $this->option = $option;
	}
    function newSelection(IdentityObject $obj){
      if($this->option=="DELETE"){
		  $core = "DELETE FROM ".$this->table;
      }else{
		  $fields = implode(',',$obj->getObjectFields());
		  $core = "SELECT $fields FROM ".$this->table;
      }
		list($where,$values) = $this->buildWhere($obj);
		return array($core." ".$where,$values);
	}
	protected function buildWhere(IdentityObject $obj){
		if($obj->isVoid()){
			return array("",array());
		}
		$compstrings = array();
		$values = array();
		foreach($obj->getComps() as $comp){
      switch($comp['operator']){
        case "IN":// 等于数组中的每个值
          $plist = implode(',', array_fill(0, count($comp['value']), '?'));
  			  $compstrings[] = "{$comp['name']} {$comp['operator']} ($plist)";
          foreach($comp['value'] as $parm)
			    $values[] = "$parm";
          break;
        case "BT":// 大于等于数组中的第一个值，小于等于数组中的第二个值
			    $compstrings[] = "{$comp['name']} >= ?";
			    $compstrings[] = "{$comp['name']} <= ?";
          foreach($comp['value'] as $parm)
			    $values[] = "$parm";
          break;
        default:
			    $compstrings[] = "{$comp['name']} {$comp['operator']} ?";
			    $values[] = $comp['value'];
      }
		}
		$where = "WHERE ".implode(" AND ",$compstrings);
		return array($where,$values);
	}
}
?>