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

	// 通过标识对象拼接sql语句的where条件语句
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
				case 'VGT':// 版本号 > 
					$compstrings[] = $this->versionField($comp['name'])." > ?";
					$values[] = $this->versionValue($comp['value']);
				break;
				case 'VGE':// 版本号 > =
					$compstrings[] = $this->versionField($comp['name'])." >= ?";
					$values[] = $this->versionValue($comp['value']);
					break;
				case 'VLT':// 版本号 > 
					$compstrings[] = $this->versionField($comp['name'])." < ?";
					$values[] = $this->versionValue($comp['value']);
					break;
				case 'VLE':// 版本号 > =
					$compstrings[] = $this->versionField($comp['name'])." <= ?";
					$values[] =$this-> versionValue($comp['value']);
					break;
				default:
					$compstrings[] = "{$comp['name']} {$comp['operator']} ?";
					$values[] = $comp['value'];
			}
		}
		$where = "WHERE ".implode(" AND ",$compstrings);
		return array($where,$values);
	}

	// function write_to_console($data) {
	// 	$console = $data;
	// 	if (is_array($console))
	// 	$console = implode(',', $console);
	   
	// 	echo "<script>console.log('Console: " . $console . "' );</script>";
	//    }
	   
	/// 
	protected function versionField($field){
		$vers = explode('.',$field);
		$result = "CONCAT(";
			for($i=1;$i<5;$i++){
				if($i<=count($vers)) $result = $result."LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field, '.', $i), '.', -1), 5, '0'),";
				else  $result = $result."LPAD('0',5,'0'),";
			}
			// write_to_console($result);
			return substr($result,0,-1).")";
		// 	return  "{CONCAT(
		// 	LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field, '.', 1), '.', -1), 5, '0'),
		// 	LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field, '.', 2), '.', -1), 5, '0'),
		// 	LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field, '.', 3), '.', -1), 5, '0') ,
		// 	LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX($field, '.', 4), '.', -1), 5, '0') 
		//    )}";
	}
	
	protected function versionValue($version){
		$vers = explode('.',$version);
		$result = "";
		// $result = "CONCAT(";
		for($i=0;$i<4;$i++){
			if($i<count($vers)) $result = $result.sprintf("%'05s,",$vers[$i]);
			else  $result = $result."00000";
			// if($i<count($vers)) $result = $result."LPAD($vers[$i],5,'0'),";
			// else  $result = $result."LPAD('0',5,'0'),";
		}
		return $result;
		// return subst/($result,0,-1).")";
		// return  "{CONCAT(LPAD($vers[0],5,'0'), LPAD($vers[1],5,'0'), LPAD($vers[2],5,'0'), LPAD($vers[3],5,'0'))}";
	}


}
?>