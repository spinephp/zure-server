<?php
/**
* 提供 REST 服务类
* @package yrrweb
* @author  Liu xingming(lxm.lyg@gmail.com)
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("base/Registry.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");
require_once("view/ViewHelper.php");

abstract class restFactory{
	public $request = null;
	private $pdo = null;
	public function __construct(){
	}
	
	public function setRequest(\woo\controller\Request $request){
		$this->request = $request;
	}
	
	public function getRequest(){
		return $this->request;
	}
 
	public function setPDO($pdo)
	{
		$this->pdo = $pdo;
	}
 
	public function getPDO()
	{
		return $this->pdo;
	}
 
	/**
	 * 当查询条件中的字段值为 ? 号时，在 sessionRegistry 中取出字段对应的值
	 * @param $field - string 类型，指定字段
	 * @return 返回字段对应的值
	 */
	protected function autoValue($value){
		$session = \woo\base\SessionRegistry::instance();
		switch($value){
			case "?id":
				if($this->request->getProperty("cmd")=="Person")
					$value = $session->get("userid");
				break;
			case "?userid": // 当前登录用户的 id
				$value = $session->get('userid');
				break;
			case "?drymainid":
				$value = $session->get('drymainid');
				break;
			case '?time': // 当前系统时间
				$value = date("Y-m-d H:i:s",time());
				break;
		}
		return $value;
	}
	abstract function doMethod(\woo\controller\Request $request);
}

class getREST extends restFactory{
	public function doAny($target){
		return $this->getRecords($target);
	}

	/**
	 * 该方法更新由 $targets 数组内容指定的数据表中的指定记录，由子类中的 doUpdate 方法调用
	 * 根据保存在 request 类中的请求参数，生成由参数 $target 指定的域名
	 * @param $target - array, 指定要删除的对象,数组以键值对方式组织，结构为：table=>value,其中：
	 *     table - string, 指定要删除的数据表，同时指定域名目标
	 *     value - array, 指定字段名集合及查寻条件，结构为：'fields'=>array(field[，field...]),'value'=>fieldvalue | array 其中:
	 *                    
	 *         value - array, 指定查找字段名和其值的产生方法，结构为 'key'=>field0,'value'=>array('index'=>field1,其中：
	 *         field0 - string, 指定要查找的字段名
	 *         index - $target 数组的索引值，指定查找字段名的域名目标
	 *         field1 - string, 指定查找字段名的域名目标的字段名
	 * @return 若操作成功返回各被删除数据表及其被删除记录的 id 键值对，否则返回 id=-1,error=错误信息的键值对
	 */
	function getRecords($targets){
		return $this->toJSON($this->getCollection($targets));
	}
	
	/**
	 * 获取数据表 $table 的集合
	 * @param $table - string 类型，指定数据表名称
	 * @return Collection 类的实例，指定数据集合
	 */
	protected function getCollection($table){
		if(is_null($table))
			throw new \woo\base\AppException("Table name is need!");
		$target = ucfirst($table);
		$object = "woo\\domain\\".$target;
		$fields = array();    
		// 生成域名目标
		if(file_exists("domain/$target.php"))
			include_once("domain/$target.php");
		$domain = new $object(null);
		$keys = array_keys($domain->getObjects());
		$keys[] = 'id';
		$filter = $this->request->getProperty("filter");
		$extend = $this->request->getProperty("extend");
		foreach($filter as $field)
		if(in_array($field,$keys)){
			$fields[] = $field; 
		}else{ // 处理伪字段
			if($field=='names'){ //  names
				if(!in_array('name',$fields)) $fields[] = 'name';
				if(!in_array('name_en',$fields)) $fields[] = 'name_en';
			}else if(isset($extend[$field])){
				foreach($extend[$field] as $ext){
					if(in_array($ext,$keys) && !in_array($ext,$fields))
						$fields[] = $ext; 
				}
			}
		}
		$condition = $this->request->getProperty("cond");
		if(!empty($condition)){
			foreach($condition as $cond){
				$field = $cond['field'];
				if(in_array($field,$keys) && !in_array($field,$fields))
					$fields[] = $field; 
			}
		}

		$factory = \woo\mapper\PersistenceFactory::getFactory($table,$fields);
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $this->buildIdentityObject($factory->getIdentityObject());
		return $finder->find($idobj);
	}
	
	/**
	 * 根据要求(由 $filter 指定的字段),把查寻到的数据库记录转换为 JSON 键值对
	 * @param $collection - Collection 类的实例，包含要查寻的数据集合
	 * @return JSON 键值对，包含查寻到的数据
	 */
	protected function toJSON($collection){
		//$i = 0;
		$result = array();
		$filter = $this->request->getProperty("filter");
		for($i=0;$i<$collection->count();++$i){
		//foreach($collection as $rec){
			$rec = $collection->current();
			if(is_null($rec))
				throw new \woo\base\AppException("The record is null!");
			foreach($filter as $field){
				if(!is_null($field)){
					$name = "get".ucfirst($field);
					if(method_exists($rec,$name)){
						$params = $this->request->getProperty("params");
						if(isset($params) && isset($params[$field]))
							$result[$i][$field] = $rec->$name($params[$field]);
						else
							$result[$i][$field] = $rec->$name();
					}else
						throw new  \woo\base\AppException("Invalid field name!");
				}
			}
			$collection->seek(1);
			//$i++;
		}
		//$this->request->log(json_encode($result));
		return $result;
	}
  
	/**
	 * 修改标识对象，把查询条件加入其中
	 * @param $idobj - IdentityObject 类的实例，加入查询条件之前的标识对象
	 * @return IdentityObject 类的实例，加入查询条件后的标识对象
	 */
	protected function buildIdentityObject($idobj){
		$cond = $this->request->getProperty("cond");
		if(!empty($cond)){
			foreach($cond as $item){
				$fun = $item["operator"];
				$value = $this->autoValue($item["value"]);
				$idobj = $idobj->field($item["field"])->$fun($value);
			}
		}
		return $idobj;
	}
  
	/**
	* 处理语言伪字段(非数据库字段，由数据库表中相关中文字段名加后缀 s 组成。伪字段只能读出，不能写入)
	* 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
	* 
	*/
	protected function pseudoLanguageFields($extends){
		$need = $this->request->getProperty("extend");
		$filter = $this->request->getProperty("filter");

		foreach($extends as $ext){
			if(in_array("{$ext}s",$filter)){
				$need["{$ext}s"] = array($ext,"{$ext}_en");
			}
		}
		if(!is_null($need))
			$this->request->setProperty('extend',$need);  
	}
  
	/**
	* 处理伪字段(非数据库字段，通常由数据库表中相关字段和特定字符按一定顺序组合而成的复合数据),伪字段只能读出，不能写入。
	* 把与伪字段相关的字段列出，以键名为 extend 保存到 Request 类管理的白板中.
	*
	*/
	protected function pseudoFields($extends){
		$need = $this->request->getProperty("extend");
		$filter = $this->request->getProperty("filter");

		foreach($extends as $pse=>$ext){
			if(in_array($pse,$filter)){
				$need[$pse] = $ext;
			}
		}
		if(!is_null($need))
			$this->request->setProperty('extend',$need);  
	}
	
	public function doMethod(\woo\controller\Request $request){
		try{
			$this->request = $request;
			$cmdStatus = $request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);
			$target = strtolower($request->getProperty("cmd"));
			if(is_null($target))
				throw new \woo\base\AppException("Invalid request parameter!");
			return $this->doAny($target);
		}catch(\woo\base\AppException $e){
			$pdo = $this->getPDO();
			if(!empty($pdo)) $pdo->rollBack();               //回滚事务  
			throw new \woo\base\AppException($e);
		}
	}
	
}

class changeFactory extends restFactory{
	//protected $pdo = null;
  
	/**
	 * 该方法根据 $condition 数组内容设置领域对象 $domain[$index] 的内容。
	 * @param $condition - array, 指定要设置领域对象的数据,数组以键值对方式组织，结构为：
	 *     1. fieldname=>fieldvalue,其中：
	 *          fieldname - string, 指定字段名
	 *          fieldvalue - string,指定字段值
	 *     2. fieldname=>array('index'=>fieldname1)
	 *          fieldname - string, 指定字段名
	 *          index - int,指定数据表索引
	 *          fieldname2 - string, 由 index 指定的数据表中的字段名
	 * @return 若操作成功返回 $condition 键数组,否则抛出错误信息，无返回
	 */
	private function conditionFields($condition,&$domain,$index){
		$fields = array();
		$domaini = $domain[$index];
		if(is_array($condition)){
			foreach($condition as $field=>$value){
				$fields[] = $field;
				if(is_array($value)){
					$domaink = $domain[key($value)];
					$fun = "get".ucfirst(current($value));
					if(!method_exists($domaink,$fun))
						throw new  \woo\base\AppException("Invalid field name or field value!");
					$val = $domaink->$fun();
				}else
					$val = $value;
				$fun = "set".ucfirst($field);
				if(!method_exists($domaini,$fun))
					throw new  \woo\base\AppException("Invalid field name or field value!");
				$domaini->$fun($this->autoValue($val));
			}
		}else{
			$domaini->setId($condition);
			$fields[] = 'id';
		}
		return $fields;
	}
  
	/**
	 * 该方法根据 $main 数组内容设置领域对象 $domaini 的内容。并返回 $main 键数组。
	 * @param $main - array, 指定要设置领域对象的数据,数组以键值对方式组织，
	 *      结构为：fieldname=>fieldvalue,其中：
	 *          fieldname - string, 指定字段名
	 *          fieldvalue - string,指定字段值
	 * @return 若操作成功返回 $main 键数组,否则抛出错误信息，无返回
	 */
	private function mainFields(&$main,&$domaini){
		$fields = array();
		foreach($main as $key=>$value){
			$fields[] = $key;
			$fun = "set".ucfirst($key);
			if(!method_exists($domaini,$fun))
				throw new  \woo\base\AppException("Invalid field name $key or field value!");
			$value = $this->autoValue($value);
			$main[$key] = $value;
			if($value!=="")
				$domaini->$fun($value);
		}
		return $fields;
	}
  
	/**
	 * 该方法向数据表 $target 写入数据。
	 * @param $target - string, 指定要写入的数据表名称
	 * @param $datas - array, 键值对的数组，包含要写入的数据及其关系
	 * @param $domain - array, 领域对象数组
	 * @param $index - int, 当前的领域对象数组索引值
	 * @param $isinsert - bool, $isinsert=true 时执行插入操作，$isinsert=false 时执行更新操作
	 * @return 若操作成功返回由写入数据(字段名与字段值键值对)组成的数组,否则抛出错误信息，无返回
	 */
	private function saveRecords($target,$datas,&$domain,$index,$isinsert=true){
		$result = array();
		$finder = null;
		$pdo = $this->getPDO();
		foreach ($datas as $dIndex=>$data){
			$fields = array();
			$main = null;
			$condition = null;
			if(!empty($data['condition'])){
				$condition = $data['condition'];
				$fields = array_merge($fields,$this->conditionFields($condition,$domain,$index));
			}

			if(!empty($data['fields'])){
				$main = $data['fields'];
				if($isinsert && isset($main["id"]))
					unset($main["id"]);
				$fields = array_merge($fields,$this->mainFields($main,$domain[$index]));
			}

			if(is_null($finder)){
				if(!empty($data['need']) && is_array($data['need']))
					$fields = array_merge($fields,$data['need']);
				$finder = \woo\mapper\PersistenceFactory::getFinder($target,array_unique($fields));
				if(is_null($pdo)){
					$pdo = \woo\mapper\DomainObjectAssembler::getPDO();
					$this->setPDO($pdo);
					$pdo->beginTransaction();       //开始事务  
				}
			}

			if(!empty($main) || !empty($condition)){
				// 把数据插入表 $target 中
				$finder->insert($domain[$index]);
				if(!empty($main)){
					if($isinsert)
						$main['id'] = $domain[$index]->getId();
					$result[] = $main;
					$this->request->log(json_encode($result));
				}

				if(!empty($data['sucess'])){
					$this->$data['sucess']($domain[$index],$finder,$result);
				}

				// 插入多条记录时，每条记录的 id 字段值清零 
				if($isinsert && $dIndex < count($datas)-1)
					$domain[$index]->nullId();
			}
		}
		return $result;
	}
  
	/**
	 * 该方法更新由 $targets 数组内容指定的数据表中的指定记录，由子类中的 doUpdate 方法调用
	 * 根据保存在 request 类中的请求参数，生成由参数 $target 指定的域名
	 * @param $target - array, 指定要删除的对象,数组以键值对方式组织，结构为：table=>value,其中：
	 *      table - string, 指定要删除的数据表，同时指定域名目标
	 *      value - array, 指定字段名集合及查寻条件，结构为：'fields'=>array(field[，field...]),'value'=>fieldvalue | array 其中：
	 *                    
	 *      value - array, 指定查找字段名和其值的产生方法，结构为 'key'=>field0,'value'=>array('index'=>field1,其中：
	 *          field0 - string, 指定要查找的字段名
	 *          index - $target 数组的索引值，指定查找字段名的域名目标
	 *          field1 - string, 指定查找字段名的域名目标的字段名
	 * @return 若操作成功返回主表更新记录的 id 字段与其值的键值对及各被更新数据表及其被更新记录的字段名和字段值键值对的数组，
	 *         否则返回 id=-1,error=错误信息的键值对
	 */
	function changeRecords($targets,$sucess,$is_new){
		$domain = array();
		$result = array();
		
		$index = 0;
		$len = count($targets)-1;
		foreach($targets as $target=>$datas){
			include_once("domain/".ucfirst($target).".php");
			$object = "woo\\domain\\".ucfirst($target);
			$domain[$index] = new $object(null);  // 生成域名目标
			$_result = $this->saveRecords($target,$datas,$domain,$index,$is_new);
			if(!empty($_result))
				$result[$target] = $_result;
			$index++;
		}
		$pdo = $this->getPDO();
		if(!is_null($pdo))
			$pdo->commit();                 //提交事务  
		$result["id"] = $domain[0]->getId();
		if(!empty($sucess))
			$sucess($domain,$result);
		return $result;
	}
  
	protected function processWatermask(\woo\controller\Request $request){
		$item = $request->getProperty("item");
		if(isset($item['watermask']) && $item['watermask']=='on'){
			$target = strtolower($request->getProperty("cmd"));
			$waterText = "";
			$waterImage = "";
			$fontFile = "./mnjcy.TTF";
			$fontSize = 12;
			$textColor = "#CCCCCC";
			$xOffset = 0;
			$yOffset = 0;
			$waterPos = 0;
			$picture = $item[$target]['picture'];
			$groundImage = "images/good/";
			if(!strpos($picture,"_"))
				$groundImage .= session_id()."/";
			$groundImage .= $picture;
			if($item['watersel']=='0'){
				$waterText = $item['watermasktxt'];
				if(isset( $item['fontfile'])) $fontFile = $item['fontfile'];
				if(isset( $item['fontsize'])) $fontSize = $item['fontsize'];
				if(isset( $item['textcolor'])) $textColor = $item['textcolor'];
				if(isset( $item['xoffset'])) $xOffset = $item['xoffset'];
				if(isset( $item['yoffset'])) $yOffset = $item['yoffset'];
				if(isset( $item['waterpos'])) $waterPos = $item['waterpos'];
			}
			else
				$waterImage = "images/maskimg.png";
			$ret = imageWaterMark($groundImage,$waterPos,$waterImage,$waterText,$fontSize,$textColor, $fontFile,$xOffset,$yOffset);
			if($ret != 0){
				$err = array(
					"Invalid water mask image!",
					"None background image!",
					"Background image size too small!",
					"None font file!",
					"Invalid font color!",
					"Invalid background image!");
				throw new  \woo\base\AppException($err[$ret-1]);
			}
		}
	}
	
	public function doMethod(\woo\controller\Request $request){
		try{
			$this->request = $request;
			$cmdStatus = $request->getFeedbackString();
			if($cmdStatus != "Command Ok!")
				throw new \woo\base\AppException($cmdStatus);
			$item = $request->getProperty("item");
			if(is_null($item))
				throw new \woo\base\AppException("None item, data  is null!");//$request->getFeedbackString());
			$this->doBefore($item);
			$result = $this->doAny($item);
			//$this->request->log("item=".json_encode($item));
			$this->doAfter($result,$item);
			return $result;
		}catch(\woo\base\AppException $e){
			$pdo = $this->getPDO();
			if(!empty($pdo)) $pdo->rollBack();               //回滚事务  
			throw new \woo\base\AppException($e);
		}
	}

	// 删除文件夹和文件夹下的所有文件
	static function deldir($dir) {
		//先删除目录下的文件：
		$dh=opendir($dir);
		while ($file=readdir($dh)) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					deldir($fullpath);
				}
			}
		}

		closedir($dh);
		//删除当前文件夹：
		if(rmdir($dir)) {
			return true;
		} else {
			return false;
		}
	}
	
	function doAfter(&$result,$item){}

	function doBefore(&$item){}
	
	function doAny(&$item){}
}

class postREST extends changeFactory{
	
	public function doAny(&$item){
		$target = array();
		$_target = strtolower($this->request->getProperty("cmd"));
		$tem = isset($item[$_target])? $item[$_target]:$item;
		$target[$_target][] = array('fields'=>$tem);
		
		if(isset($item["_processimage"])){
			// 处理图片水印
			$this->processWatermask($this->request) ;   
			$target[$_target][0]['sucess'] = $item["_processimage"];
			unset($item["_processimage"]);
		}
		
		$result = $this->changeRecords($target,function($domain,&$result) use($item,$_target){

			$s = $result[$_target][0];
			unset($result[$_target]);
			if(empty($item[$_target])){
				unset($result['id']);
				$result = $s;
			}else{
				$result[$_target][0] = $s;
			}
		},true);
		
		/**
		* 用户和系统指定的其它表操作
		*/
		$is = "?{$_target}:";
		$islen = strlen($is);
		$s = $result[$_target];
		
		foreach(array('other','system') as $opt)
			if(isset($item[$opt])){
				foreach($item[$opt] as $index=>$other){
					if(isset($other['data'])){
						$target = array();
						foreach($other['data'] as $key=>$val){
							if(strlen($val)>$islen && strpos($val,$is)==0){
								$other['data'][$key] = $s[substr($val,$islen)];
							}
						}
						$target[$other['table']][] = array('fields'=>$other['data']);
						$opse = $other['method']=='post'? true:false;
						$temp = $this->changeRecords($target,null,$opse);
						$result[$other['table']][0] = $temp[$other['table']][0];
					}
				}
			}
		return $result;
	}
}

class putREST extends changeFactory{
	public function doAny(&$item){
		$target = array();
		$_target = strtolower($this->request->getProperty("cmd"));
		$owner = empty($item[$_target]);
		$tem = isset($item[$_target])? $item[$_target]:$item;
		$target[$_target][] = array('fields'=>$tem,'condition'=>$this->request->getProperty("id"));
		
		if(isset($item["_processimage"])){
			// 处理图片水印
			$this->processWatermask($this->request) ;   
			$target[$_target][0]['sucess'] = $item["_processimage"];
			unset($item["_processimage"]);
		}
		//$this->request->log(json_encode($target));
		return  $this->changeRecords($target,function($domain,&$result) use($owner,$_target){
			$s = $result[$_target][0];
			unset($result[$_target]);
			if($owner){
				unset($result['id']);
				$result = $s;
			}else{
				$result[$_target] [0]= $s;
			}
		},false);
	}
}


class deleteREST extends restFactory{
	//private $pdo = null;
	public function doMethod(\woo\controller\Request $request){
		try{
			$this->request = $request;
			$cmdStatus = $request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);
			$table = strtolower($request->getProperty("cmd"));
			if(is_null($table))
				throw new \woo\base\AppException("Invalid request parameter!");
			$id = $this->request->getProperty("id");
			if(is_null($id))
				throw new \woo\base\AppException("Invalid id!");
			$this->setPDO(\woo\mapper\DomainObjectAssembler::getPDO());
			return $this->doAny($table,$id);
		}catch(\woo\base\AppException $e){
			$pdo =$this->getPDO();
			if(!empty($pdo)) $pdo->rollBack();               //回滚事务  
			throw new \woo\base\AppException($e);
		}
	}
  
	public function doAny($table,$id){
		$target[$table] = array('fields'=>array('id'),'value'=>$id);
		//$this->pdo = \woo\mapper\DomainObjectAssembler::getPDO();
		return $this->deleteRecords($target,function($domain,&$result)  use($target){
			$result = $result[key($target)];
		});
	}
	
	/**
	 * 该方法删除由 $targets 数组内容指定的数据表中的指定记录，由子类中的 doDelete 方法调用
	 * 根据保存在 request 类中的请求参数，生成由参数 $target 指定的领域模型
	 * @param $target - array, 指定要删除的对象,数组以键值对方式组织，结构为：table=>value,其中：
	 *     table - string, 指定要删除的数据表，同时指定领域模型
	 *     value - array, 指定字段名集合及查寻条件，结构为：'fields'=>array(field[，field...]),'value'=>fieldvalue | array 其中：
	 *                    
	 *     value - array, 指定查找字段名和其值的产生方法，结构为 'key'=>field0,'value'=>array('index'=>field1,其中：
	 *         field0 - string, 指定要查找的字段名
	 *         index - $target 数组的索引值，指定查找字段名的领域模型
	 *         field1 - string, 指定查找字段名的领域模型的字段名
	 * @return 若操作成功返回各被删除数据表及其被删除记录的 id 键值对，否则返回 id=-1,error=错误信息的键值对
	 */
	function deleteRecords($targets,$sucess){
		$rec=array();
		$index = 0;
		$pdo = $this->getPDO();
		foreach($targets as $target=>$condition){
			if(!is_array($condition))
				throw new \woo\base\AppException("Data format error!");
			include_once("domain/".ucfirst($target).".php");
			$factory = \woo\mapper\PersistenceFactory::getFactory($target,$condition['fields']);
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$key = $condition['fields'][0];
			$value = $condition['value'];
			if(is_array($value)){
				$fun = "get".ucfirst(current($value));
				$val = $rec[key($value)]->$fun();
			}else
				$val = $value;
			$idobj = $factory->getIdentityObject()->field($key)->eq($val);
			$collection = $finder->find($idobj);
			$rec[$index] = $collection->current();
			if($index==0){
				//$this->pdo = \woo\mapper\DomainObjectAssembler::getPDO();
				$pdo->beginTransaction();       //开始事务  
			}
			$recc = $rec[$index++];
			if(!is_null($recc)){
				$finder->delete($idobj);
				$result[$target] = array("id"=>$recc->getId());

				if(!empty($condition['sucess'])){
					$condition['sucess']($recc,$result[$target]);
				}
			}else if(is_array($condition))
				continue;
			else throw new \woo\base\AppException("Record ID is'n exist!");

		}

		$pdo->commit();                 //提交事务  
		if(!empty($sucess))
			$sucess($rec,$result);

		return $result;
	}
}

class REST{
	private $factory;
	public $_allow = array();
	public $json_content_type = "application/json";
	public $xml_content_type = "application/xml";

	private $_method = "";		
	private $_code = 200;

	protected $request;
	
	public function __construct($target=null,$response=true){
		try{
			$this->request = \woo\view\VH::getRequest();

			$cmdStatus = $this->request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);
			$name = strtolower($this->request->getMethod());
			if(!empty($target)){
				$temp = ucfirst($target);
				if(file_exists("view/$temp.php"))
					include_once("view/$temp.php");
				if(class_exists("woo\\view\\".$name.$temp."REST",false))
					$name .= $temp;
			}
			$name = "\\woo\\view\\{$name}REST";
			$this->factory = new $name();
			$result = $this->factory->doMethod($this->request);
			if($response)
				$this->response(json_encode($result));
			else
				return $result;
		}catch(\woo\base\AppException $e){
			$result['id'] = -1;
			$result['error'] = $e->getMessage();
			if($response)
				$this->response(json_encode($result));
			else
				return $result;
		}
	}

	function verifyUpdate(\woo\controller\Request $request){
		$cmdStatus = $request->getFeedbackString();
		if($cmdStatus!="Command Ok!")
			throw new \woo\base\AppException($cmdStatus);

		$item = $request->getProperty("item");
		if(is_null($item))
			throw new \woo\base\AppException($request->getFeedbackString());
		return $item;
	}
  
	public function get_referer(){
		return $_SERVER['HTTP_REFERER'];
	}

	public function response($data,$status=200,$format='json'){
		$this->_code = ($status)?$status:200;
		$this->set_headers($format,strlen($data));
		echo $data;
		exit;
	}

	private function get_status_message(){
		$status = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($status[$this->_code])?$status[$this->_code]:$status[500];
	}

	private function set_headers($format,$datalen){
		header("HTTP/1.1 ".$this->_code." ".$this->get_status_message());
		//header("Content-Type:".$this->_content_type);
		$contenttype = "";
		if($format =='json')
		{
			$contenttype = $this->json_content_type;
		}
		elseif($format =='xml')
		{
			$contenttype = $this->xml_content_type;
		}
		else
		{
			$contenttype = "text/plain";
		}
		header("Content-Type:".$contenttype."; charset=utf-8");
		$host  = $_SERVER['HTTP_HOST'];
		//header("Location: ".$host); $this->request->log($host);
		header("Content-Length: " . $datalen);
		header("Connection: keep-alive");
	}
}
?>
