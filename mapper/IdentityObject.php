<?php
namespace woo\mapper;
require_once("mapper/Field.php");

class IdentityObject{
	protected $currentfield=null;
	protected $fields = array();
	private $and=null;
	private $enforce = array();

	// 标识对象实例化时可以不带参数，也可以以字段名为参数
	function __construct($field=null,array $enforce=null){
		if(!is_null($enforce)){
			$this->enforce = $enforce;
		}
		if(!is_null($field)){
			$this->field($field);
		}
	}

  /**
    * 返回需要的字段名称
    * 为防止字段名中存在 MySql 中的保留字，对字段名进行了等效处理
    * @params void
    * @return array - 字段名数组
    */
	function getObjectFields(){
    $fields = $this->enforce;
    array_walk($fields, function($val,$key) use(&$fields){ 
      $fields[$key] = "`$val`";
    });
		return $fields;
	}

	// 使用一个新字段
	// 如果当前字段不完整，将抛出一个错误
	// 例如，age而不是 age > 40
	// 本方法返回当前对象的引用
	// 所以我们可以使用流畅语法
	function field($fieldname){
		if(!$this->isVoid() && $this->currentfield->isIncomplete()){
			throw new \Exception("Incomplete field");
		}
		$this->enforceField($fieldname);
		if(isset($this->fields[$fieldname])){
			$this->currentfield = $this->fields[$fieldname];
		}else{
			$this->currentfield = new Field($fieldname);
			$this->fields[$fieldname] = $this->currentfield;
		}
		return $this;
	}

	// 标识对象是否已设置了字段
	function isVoid(){
		return empty($this->fields);
	}

	// 传入的字段名是否合法
	function enforceField($fieldname){
		if(!in_array($fieldname,$this->enforce) && !empty($this->enforce)){
			$forcelist = implode(', ',$this->enforce);
			throw new \Exception("{$fieldname} not a legal field {$forcelist}");
		}
	}

	// 给当前字段添加一个相等操作符
	// 例如 'age' 变成 age=40
	// 本方法返回当前对象的引用(通过operator())
	function eq($value){
		return $this->operator("=",$value);
	}

	// 小于
	function lt($value){
		return $this->operator("<",$value);
	}

	// 大于
	function gt($value){
		return $this->operator(">",$value);
	}

	// 小于等于
	function le($value){
		return $this->operator("<=",$value);
	}

	// 大于等于
	function ge($value){
		return $this->operator(">=",$value);
	}

	// 等于数组中的值
	function in($value){
    return $this->operator("IN",$value);
	}

	// 在两个数组值之间
	function bt($value){
    return $this->operator("BT",$value);
	}

	// 操作符方法是否得到当前字段并添加了操作符和测试值
	private function operator($symbol,$value){
		if($this->isVoid()){
			throw new \Exception("no object field defined");
		}
		$this->currentfield->addTest($symbol,$value);
		return $this;
	}

	// 以关联数组形式返回目前创建的所有对比
	function getComps(){
		$ret = array();
		foreach($this->fields as $key=>$field){
			$ret = array_merge($ret,$field->getComps());
		}
		return $ret;
	}
}

?>