<?php
namespace woo\command;

require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("mapper/DomainObjectAssembler.php");
require_once("mapper/PersistenceFactory.php");
require_once("domain/Employee.php");
abstract class Command{
    private static $STATUS_STRINGS = array(
        'CMD_DEFAULT'=>0,
        'CMD_OK' => 1,
        'CMD_ERROR' =>2,
        'CMD_INSUFFICIENT_DATA' => 3
    );
    private $status = 0;
    
    protected static $DATA_RIGHT = array(
	'Bill'=>array('userShell',null,null,null),
	'BillSale'=>array('userShell',null,null,null),
	'CarriageCharge'=>array('userShell',null,null,null),
	'CarriageClass'=>array('userShell',null,null,null),
	'Cart'=>array('userShell',null,null,null),
	'Company'=>array('userShell',null,null,null),
	'Complain'=>array('userShell',null,null,null),
	'Consignee'=>array('userShell',null,null,null),
	'Currency'=>array(null,'employeeShell','employeeShell','employeeShell'),
 	'Custom'=>array('userShell','captchaShell userShell','captchaShell userShell','captchaShell userShell'),
	'CustomAccount'=>array('userShell',null,null,null),
  	'Department'=>array('employeeShell','captchaShell employeeShell','captchaShell employeeShell','captchaShell employeeShell'),
	'District '=>array('userShell',null,null,null),
 	'Employee'=>array('userShell','captchaShell employeeShell','captchaShell employeeShell','captchaShell employeeShell'),
	'Language'=>array(null,'employeeShell','employeeShell','employeeShell'),
	'Navigation'=>array(null,'employeeShell','employeeShell','employeeShell'),
	'Order'=>array('userShell',null,null,null),
	'OrderComplain'=>array('userShell',null,null,null),
	'OrdersState'=>array('userShell',null,null,null),
  	'OrderState'=>array('userShell','captchaShell employeeShell','captchaShell employeeShell','captchaShell employeeShell'),
	'Payment'=>array('userShell',null,null,null),
  	'Product'=>array(null,'captchaShell employeeShell','captchaShell employeeShell','captchaShell employeeShell'),
  	'ProductClass'=>array(null,'captchaShell employeeShell','captchaShell employeeShell','captchaShell employeeShell'),
	'Right'=>array('employeeShell','employeeShell','employeeShell','employeeShell'),
	'Transport'=>array('userShell',null,null,null)
     );

    final function __construct(){}

    function execute(\woo\controller\Request $request){
        $this->status = $this->doExecute($request);
        $request->setCommand($this);
    }
	
	/**
	 * 验证 token 标志后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 本类成员方法
	 * @return command status word
	 */
	protected function safeShell(\woo\controller\Request $request,$fun){
		$session = \woo\base\SessionRegistry::instance();
		$result =self::statuses('CMD_INSUFFICIENT_DATA');
		$token = $request->getProperty("token");
    if(!isset($token)){
      $item = $request->getProperty("item");
      $token = $item['token'];
    }
		if($token==$session->get("token") || $token==session_id()){
				$result = $this->$fun($request);
			}else{
				$request->addFeedback("Access Denied");
			}
			return $result;
		}
	
	/**
	 * 验证用户后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function userShell(\woo\controller\Request $request,$fun){
		$session = \woo\base\SessionRegistry::instance();
		$userid = $session->get("userid");
		$result = 'CMD_INSUFFICIENT_DATA';
		if(isset($userid)){
			$result = $fun($request,$userid);
		}else{
			$request->addFeedback("Not logged!");
		}
		return is_numeric($result)? $result:self::statuses($result);
	}
	
	/**
	 * 验证是公司员工后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function employeeShell(\woo\controller\Request $request,$fun){
		$session = \woo\base\SessionRegistry::instance();
		$userid = $session->get("userid");
		$result = 'CMD_INSUFFICIENT_DATA';
		if(isset($userid)){
      $factory = \woo\mapper\PersistenceFactory::getFactory("employee",array("id","userid"));
      $finder = new \woo\mapper\DomainObjectAssembler($factory);
      $idobj = $factory->getIdentityObject()->field("userid")->eq($userid);
      $collection = $finder->find($idobj);
      if($collection->count()){
        $obj = $collection->current();
        if($obj){
          if($obj->getUserid()==$userid){
			      $result = $fun($request,$userid);
          }
        }
      }else
			  $request->addFeedback("No the employee!");
		}else{
			$request->addFeedback("Not logged!");
		}
		return is_numeric($result)? $result:self::statuses($result);
	}
	
	/**
	 * 验证验证码后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function captchaShell(\woo\controller\Request $request,$fun){
		$session = \woo\base\SessionRegistry::instance();
		$vcodes = $session->get('saftcode');
		$session->set('saftcode',rand(1000,9999));
		$code = $request->getProperty("code");
    if(!isset($code)){
      $item = $request->getProperty("item");
      if(isset($item))
        $code = $item["code"];
    }
    if(isset($code)){
		  $result = 'CMD_OK';
		  if($vcodes==$code) // 校验码是否正确
			  $result = $fun($request);
		  else{
			  $request->addFeedback("Validate Code Error!");
			  $result = 'CMD_INSUFFICIENT_DATA';
		  }
    }else{
			$request->addFeedback("None Validate Code!");
			$result = 'CMD_INSUFFICIENT_DATA';
    }
		return self::statuses($result);
	}

    function getStatus(){
        return $this->status;
    }
    
    static function statuses($str='CMD_DEFAULT'){
        if(empty($str)){ $str = 'CMD_DEFAULT';}
        if(is_numeric($str)) return $str;
        return self::$STATUS_STRINGS[$str];
    }

    static function validates($cmd){
        if(empty($cmd) || !array_key_exists($cmd,self::$DATA_RIGHT))
		return null;
         return self::$DATA_RIGHT[$cmd];
    }

    abstract function doExecute(\woo\controller\Request $request);
}

class RESTCommand extends Command{
	function doExecute(\woo\controller\Request $request){
		switch($_SERVER["REQUEST_METHOD"]){
			case "GET": $this->safeShell($request,'restGet');break;
			case "POST": $this->safeShell($request,'restCreate');break;
			case "PUT": $this->safeShell($request,'restUpdate');break;
			case "DELETE": $this->safeShell($request,'restDelete');break;
		}
	}
	
	/**
	 * 
	 */
	function restGet(\woo\controller\Request $request){ 
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		if(empty($right) || empty($right[0])){
			$request->addFeedback("Command Ok!");
			return 'CMD_OK';
		}else{
			$a = explode(' ',$right[0]);
			return $this->$a[0]($request,function($request){ 
				$request->addFeedback("Command Ok!");
				return 'CMD_OK';
			});
		}
	}
	
	private function _restCreate(\woo\controller\Request $request){
		$item = $request->getProperty("item");
		$status = 'CMD_OK';
		if(isset($item) && (!isset($item["id"]) || substr($item["id"],0,2)=='c-')){
			/*if(empty($item['userid'])){
				$session = \woo\base\SessionRegistry::instance();
				$item['userid'] = $session->get('userid');
				$request->setProperty("item",$item);
			}*/
			if(isset($item['action'])){
				$cmd = $request->getProperty('cmd');
				$s = lcFirst($cmd);
				if($item['action']=="{$s}_create"){
					$request->addFeedback("Command Ok!");
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback("Command Ok!");
			}
		}else{
			$request->addFeedback("ID is needed!");
			$result = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}
	
	function restCreate(\woo\controller\Request $request){
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		if(empty($right) || empty($right[1])){
			return $this->userShell($request,function($request){
				return $this->_restCreate($request);
			});
		}else{
			$a = explode(' ',$right[1]);
			return $this->$a[0]($request,function($request,$a) { 
				if(count($a)==2){
					return $this->$a[1]($request,function($request){ 
						return $this->_restCreate($request);
					});
				}else{
					return $this->_restCreate($request);
				}
			});
		}
	}
	
	private function _restUpdate(\woo\controller\Request $request){
		$item = $request->getProperty("item");
		$status = 'CMD_OK';
		if(isset($item) && isset($item["id"])){
			if(isset($item['action'])){
				$cmd = $request->getProperty('cmd');
				$s = lcFirst($cmd);
				if($item['action']=="{$s}_update"){
					$request->addFeedback("Command Ok!");
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback("Command Ok!");
			}
		}else{
			$request->addFeedback("ID is needed!");
			$result = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}

	function restUpdate(\woo\controller\Request $request){
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		if(empty($right) || empty($right[2])){
			return $this->userShell($request,function($request){
				return $this->_restUpdate($request);
			});
		}else{
			$a = explode(' ',$right[2]);
			return $this->$a[0]($request,function($request,$a) { 
				if(count($a)==2){
					return $this->$a[1]($request,function($request){ 
						return $this->_restUpdate($request);
					});
				}else{
					return $this->_restUpdate($request);
				}
			});
		}
	}
	
	private function _restDelete(\woo\controller\Request $request){
		$status = 'CMD_OK';
		$id = $request->getProperty("id");
		$action = $request->getProperty("action");
		if(isset($id) && is_numeric($id)){
			if(isset($action)){
				$cmd = $request->getProperty('cmd');
				$s = lcFirst($cmd);
				if($action=="{$s}_delete"){
					$request->addFeedback("Command Ok!");
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback("Command Ok!");
			}
		}else{
			$request->addFeedback("ID is needed!");
			$result = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}

	function restDelete(\woo\controller\Request $request){
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		if(empty($right) || empty($right[2])){
			return $this->userShell($request,function($request){
				return $this->_restDelete($request);
			});
		}else{
			$a = explode(' ',$right[2]);
			return $this->$a[0]($request,function($request,$a) { 
				if(count($a)==2){
					return $this->$a[1]($request,function($request){ 
						return $this->_restDelete($request);
					});
				}else{
					return $this->_restDelete($request);
				}
			});
		}
	}
}

class DefaultCommand extends Command{
    function doExecute(\woo\controller\Request $request){
        $request->addFeedback("Welcome to WOO");
        include("view/main.php");
    }
}
?>