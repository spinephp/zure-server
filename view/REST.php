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

class REST{
	public $_allow = array();
	public $json_content_type = "application/json";
	public $xml_content_type = "application/xml";

	private $_method = "";		
	private $_code = 200;

	protected $request;
	protected $_target;
	
	public function __construct($target=null){
		try{
			$this->_target = $target;
			$this->request = \woo\view\VH::getRequest();
			$this->pdo = null;

			$cmdStatus = $this->request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);

			switch($this->request->getMethod()){
				case "GET": $this->restGet();break;
				case "POST": $this->restCreate();break;
				case "PUT": $this->restUpdate();break;
				case "DELETE": $this->restDelete();break;
			}
		}catch(\woo\base\AppException $e){
			if(!empty($this->pdo)) $this->pdo->rollBack();               //回滚事务  
			$result['id'] = -1;
			$result['error'] = $e->getMessage();
			$this->response(json_encode($result));
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
	
	/**
	 * 根据要求(由 $filter 指定的字段),把查寻到的数据库记录转换为 JSON 键值对
	 * @param $collection - Collection 类的实例，包含要查寻的数据集合
	 * @return JSON 键值对，包含查寻到的数据
	 */
	protected function toJSON($collection){
		$i = 0;
		$result = array();
		$filter = $this->request->getProperty("filter");
		foreach($collection as $rec){
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
			$i++;
		}
		// $this->request->log(json_encode($result));
		return json_encode($result);
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
	 * 根据请求参数生成域名目标
	 * @param $domainobject - domain 类型，指定要生成的域名
	 * @return 无
	 */
	protected function setDomain(&$domainobject){
		$item = $this->request->getProperty("item");
		if(is_null($item))
			throw new \woo\base\AppException($this->request->getFeedbackString());
		foreach($item as $key=>$val){
			if($key!="id"){
				$fun = "set".ucfirst($key);
				if(method_exists($domainobject,$fun) && !is_null($val))
					$domainobject->$fun($val);
				else
					throw new  \woo\base\AppException("Invalid field name or field value!");
			}
		}
	}
  
	/**
	 * 该方法实现简单的实现数据表记录的创建和更新，由子类中的 restCreate 或 restUpdate 方法调用
	 * 根据保存在 request 类中的请求参数，生成由参数 $target 指定的域名
	 * @param $target - string, 指定要操作的域名
	 * @var $item - 键值对数组，指定用户请求的数据
	 * @var $domain - domain 类型，系统生成的 domain 类实例
	 * @var finder - DomainObjectAssembler 类型，系统生成的 DomainObjectAssembler 类实例
	*/
	function updateDomain($target){
		try{
			$cmdStatus = $this->request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);

			$this->item = $this->request->getProperty("item");
			if(is_null($this->item))
				throw new \woo\base\AppException("Invalid paramters");

			$data = $this->item[$target];
			if(empty($data))
				throw new \woo\base\AppException("none $target data infomation");

			$object = "woo\\domain\\".ucfirst($target);
			$factory = \woo\mapper\PersistenceFactory::getFactory($object);
			$this->finder = new \woo\mapper\DomainObjectAssembler($factory);

			// 生成域名目标
			$this->domain = new $object(null);
			foreach($data as $key=>$val){
				$fun = "set".ucfirst($key);
				if(!method_exists($this->domain,$fun))
					throw new  \woo\base\AppException("Invalid field name $key or field value!");
				if($val!="")
					$this->domain->$fun($val);
			}
			if($this->request->getMethod()=='PUT')
				$this->domain->setId($this->request->getProperty('id'));

			// 把数据插入表 $target 中
			$this->finder->insert($this->domain);

			$this->item[$target]['id'] = $this->domain->getId();
			$this->response(json_encode($this->item[$target]));
		}catch(\woo\base\AppException $e){
			$result['id'] = -1;
			$result['error'] = $e->getMessage();
			$this->response(json_encode($result));
		}
	}
	
  
	/**
	 * 该方法根据客户给定的 id 实现简单的实现数据表记录的删除，由子类中的 restDelete 方法调用
	 * 根据保存在 request 类中的请求参数，生成由参数 $target 指定的域名
	 * @param $target - string, 指定要操作的域名
	 * @return 若操作成功返回被删除记录的 id 键值对，否则返回 id=-1,error=错误信息的键值对
	 */
	function deleteDomain($target){
		try{
			$cmdStatus = $this->request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);

			$id = $this->request->getProperty("id");
			$object = "woo\\domain\\".ucfirst($target);
			$factory = \woo\mapper\PersistenceFactory::getFactory($object);
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('id')->eq($id);
			$collection = $finder->find($idobj);
			$rec = $collection->current();
			if(is_null($rec))
				throw new \woo\base\AppException("Record ID is'n exist!");

			$finder->delete($idobj);

			$this->response(json_encode(array("id"=>$id)));
		}catch(\woo\base\AppException $e){
			$this->response(json_encode(array("id"=>-1,"error"=>$e->getMessage())));
		}
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
		echo  $this->toJSON($this->getCollection($targets));
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
		$fields = array();
		$main = null;
		$condition = null;
		$finder = null;
		foreach ($datas as $data){
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
				if(is_null($this->pdo)){
					$this->pdo = \woo\mapper\DomainObjectAssembler::getPDO();
					$this->pdo->beginTransaction();       //开始事务  
				}
			}

			if(!empty($main) || !empty($condition)){
				// 把数据插入表 $target 中
				$finder->insert($domain[$index]);
				if(!empty($main)){
					if($isinsert)
						$main['id'] = $domain[$index]->getId();
					$result[] = $main;
				}

				if(!empty($data['sucess'])){
					$this->$data['sucess']($domain[$index],$finder,$result[$target]);
				}
			}
		}
		return $result;
	}
  
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
				$domaini->$fun($val);
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
		$this->pdo = null;
		$index = 0;
		$len = count($targets)-1;
		foreach($targets as $target=>$datas){
			include_once("domain/".ucfirst($target).".php");
			$object = "woo\\domain\\".ucfirst($target);
			$domain[$index] = new $object(null);  // 生成域名目标
			$_result = $this->saveRecords($target,$datas,$domain,$index,$is_new);
			if(!empty($_result))
				$result[$target] = $_result;
			if($len==$index){
				if(!is_null($this->pdo))
					$this->pdo->commit();                 //提交事务  
				$result["id"] = $domain[0]->getId();
				if(!empty($sucess))
					$sucess($domain,$result);
			}
			$index++;
		}
		return $result;
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
				$this->pdo = \woo\mapper\DomainObjectAssembler::getPDO();
				$this->pdo->beginTransaction();       //开始事务  
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

		$this->pdo->commit();                 //提交事务  
		if(!empty($sucess))
			$sucess($rec,$result);

		$this->response(json_encode($result));
	}
  
	function restGet(){
		$this->doGet();
	}

	function restCreate(){
		$this->doCreate($this->request->getProperty("item"));
	}

	function restUpdate(){
		$this->doUpdate($this->request->getProperty("item"));
	}

	function restDelete(){
		$this->doDelete();
	}

	function doGet(){
		$this->getRecords($this->_target);
	}

	function doCreate($item){
		$target = array();
		$_target = $this->_target;
		$owner = empty($item[$_target]);
		$tem = isset($item[$_target])? $item[$_target]:$item;
		$target[$_target][] = array('fields'=>$tem);
		$result = $this->changeRecords($target,function($domain,&$result) use($owner,$item,$_target){

			$s = $result[$_target][0];
			unset($result[$_target]);
			if($owner){
				unset($result['id']);
				$result = $s;
			}else{
				$result[$_target] = $s;
			}
		},true);
		
		/**
		* 用户和系统指定的其它表操作
		*/
		$is = "?{$_target}:";
		$islen = strlen($is);
		
		foreach(array('other','system') as $opt)
			if(isset($item[$opt])){
				foreach($item[$opt] as $index=>$other){
					if(isset($other['data'])){
						foreach($other['data'] as $key=>$val){
							if(strlen($val)>$islen && strpos($val,$is)==0){
								$other['data'][$key] = $s[substr($val,$islen)];
							}
						}
						$target[$other['table']][] = array('fields'=>$other['data']);
						$opse = $other['method']=='post'? true:false;
						$temp = $this->changeRecords($target,null,$opse);
						$result[$other['table']] = $temp[$other['table']][0];
					}
				}
			}
		$this->afterCreate($result,$item);
		$this->response(json_encode($result),201);
	}

	function afterCreate(&$result,$item){}

	/**
	 * 更新数据表记录
	 * @param $item - array, 键值对数组，支持两种数据格式
	 *          1. table:{field0:value0[,..]} 
	 *          2. {field0:value0[,..]} 
	 * @param $result - array, 键值对数组，与 $item 相对应产生两种数据格式
	 *          1. {'id':id,table:{field0:value0[,..]}} 
	 *          2. {field0:value0[,..]} 
	 *          如程序无其它错误，该数据返回到客户端
	 */
	function doUpdate($item){
		$target = array();
		$_target = $this->_target;
		$owner = empty($item[$_target]);
		$tem = isset($item[$_target])? $item[$_target]:$item;
		$target[$_target][] = array('fields'=>$tem,'condition'=>$this->request->getProperty("id"));
		$this->request->log(json_encode($target));
		$result = $this->changeRecords($target,function($domain,&$result) use($owner,$_target){
			$s = $result[$_target][0];
			unset($result[$_target]);
			if($owner){
				unset($result['id']);
				$result = $s;
			}else{
				$result[$_target] = $s;
			}
		},false);
		$this->response(json_encode($result));
	}

	function doDelete(){
		$target[$this->_target] = array('fields'=>array('id'),'value'=>$this->request->getProperty("id"));
		$this->deleteRecords($target,function($domain,&$result)  use($target){
			$result = $result[key($target)];
		});
	}
}
?>
