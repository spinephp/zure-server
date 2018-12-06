<?php
namespace woo\command;

require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("mapper/FinalAssembler.php");
require_once("domain/Employee.php");

const COMMAND_OK = "Command Ok!";

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
	'BillSale'=>array('userShell','userShell','userShell','userShell'),
	'CarriageCharge'=>array('userShell',null,null,null),
	'CarriageClass'=>array('userShell',null,null,null),
	'Cart'=>array('userShell',null,null,null),
	'Company'=>array('userShell',null,null,null),
	'Complain'=>array('userShell',null,null,null),
	'Consignee'=>array('userShell',array('fn'=>null,'autoParam'=>'userid'),null,null),
	'Currency'=>array(null,array('fn'=>'employeeShell','autoParam'=>null),'employeeShell','employeeShell'),
 	'Custom'=>array('userShell',array('fn'=>'captchaShell','autoParam'=>null),'captchaShell userShell','captchaShell userShell'),
	'CustomAccount'=>array('userShell',null,null,null),
  	'Department'=>array('employeeShell',array('fn'=>'captchaShell employeeShell','autoParam'=>null),'captchaShell employeeShell','captchaShell employeeShell'),
	'District '=>array('userShell',null,null,null),
 	'Employee'=>array('userShell',array('fn'=>'captchaShell employeeShell','autoParam'=>null),'captchaShell employeeShell','captchaShell employeeShell'),
	'Language'=>array(null,array('fn'=>'employeeShell','autoParam'=>null),'employeeShell','employeeShell'),
	'Navigation'=>array(null,array('fn'=>'employeeShell','autoParam'=>null),'employeeShell','employeeShell'),
	'Order'=>array('userShell',null,null,null),
	'OrderComplain'=>array('userShell',null,null,null),
	'OrdersState'=>array('userShell',null,null,null),
  	'OrderState'=>array('userShell',array('fn'=>'captchaShell employeeShell','autoParam'=>null),'captchaShell employeeShell','captchaShell employeeShell'),
	'Payment'=>array('userShell',null,null,null),
 	'Person'=>array(null,array('fn'=>'captchaShell','autoParam'=>null),'userShell','captchaShell userShell'),
 	'Product'=>array(null,array('fn'=>'captchaShell employeeShell','autoParam'=>null),'captchaShell employeeShell','captchaShell employeeShell'),
  	'ProductClass'=>array(null,array('fn'=>'captchaShell employeeShell','autoParam'=>null),'captchaShell employeeShell','captchaShell employeeShell'),
	'Right'=>array('employeeShell',array('fn'=>'employeeShell','autoParam'=>null),'employeeShell','employeeShell'),
	'Transport'=>array('userShell',null,null,null),
 	'MonitorScene'=>array(null,null,null,null),
 	'DryData'=>array(null,null,null,null),
 	'DryMain'=>array(null,null,null,null),
 	'Emoji'=>array(null,null,null,null)
     );

    final function __construct(){}

    function execute(\woo\controller\Request $request){
        $this->status = $this->doExecute($request);
        $request->setCommand($this);
    }
	
	/**
	 * 验证 token 标志成功后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 本类成员方法
	 * @return command status word
	 */
	protected function safeShell(\woo\controller\Request $request,$fun){
		/*$session = \woo\base\SessionRegistry::instance();
		$result = self::statuses('CMD_INSUFFICIENT_DATA');
		$token = $request->getProperty("token");
		if (!isset($token)) {
			$item = $request->getProperty("item");
			$token = $item['token'];
		}
		if (isset($token) && ($token == $session->get("token") || $token == session_id())) {
			$result = $this->$fun($request);
		} else {
			$request->addFeedback("Access Denied in command.safeShell");
		}*/
		$result = $this->$fun($request);
		return $result;
	}
	
	/**
	 * 验证用户后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function userShell(\woo\controller\Request $request,$fun=null){
		$session = \woo\base\SessionRegistry::instance();
		$userid = $session->get("userid");
		$result = 'CMD_INSUFFICIENT_DATA';
		$item = $request->getProperty('item');
		$cmd = $request->getProperty('cmd');
		if(!isset($userid) && isset($item['person']['id']))
			$userid = $item['person']['id'];
		

		if(isset($userid)){
			$result = 'CMD_OK';
			if(!empty($fun))
				$result = $fun($request,$userid);
		}else if($cmd=='Person' && isset($item['hash'])){
			// 重置密码
			$hash = $item['hash'];
			$cond = array(array("field"=>"hash","value"=>$hash,"operator"=>"eq"));
			$m = new \woo\mapper\FinalAssembler("person",array("id","hash","lasttime"),$cond);
			$objs = $m->find();
			if(count($objs)){
				$obj = $objs[0];
				if(count($obj)){
					$zero1=strtotime(date('y-m-d h:i:s')); //当前时间
					$zero2=strtotime($obj["lasttime"]);  //过年时间
					//$guonian=($zero2-$zero1)/86400; //60s*60min*24h
					if($zero2-$zero1< 2*86400){
						$userid = $obj["id"];
						$result = 'CMD_OK';
						if(!empty($fun))
							$result = $fun($request,$userid);
						$item['hash'] = '00000000000000000000000000000000';
						$request->setProperty('item',$item);
					}else{
						$request->addFeedback("Operation has expired!");
					}
				}else
					$request->addFeedback("Invalid option!");
			}else		
				$request->addFeedback("Invalid option!");
		}else
			$request->addFeedback("Not logged!");
		return is_numeric($result)? $result:self::statuses($result);
	}
	
	/**
	 * 验证是公司员工后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function employeeShell(\woo\controller\Request $request,$fun=null){
		$session = \woo\base\SessionRegistry::instance();
		$userid = $session->get("userid");
		$result = 'CMD_INSUFFICIENT_DATA';
		if(isset($userid)){
			$cond = array(array("field"=>"userid","value"=>$userid,"operator"=>"eq"));
			$m = new \woo\mapper\FinalAssembler("employee",array("id","userid"),$cond);
			$objs = $m->find();
			if(count($objs)){
				$obj = $objs[0];
				if($obj){
					if($obj["userid"]==$userid){
						$result = 'CMD_OK';
					if(!empty($fun))
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
	protected function captchaShell(\woo\controller\Request $request,$fun=null){
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
		  if($vcodes==$code){ // 校验码是否正确
			if(!empty($fun))
			  $result = $fun($request);
		  }else{
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
	
	/**
	 * 验证验证码后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
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
		switch($request->getMethod()){
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
			$request->addFeedback(COMMAND_OK);
			return 'CMD_OK';
		}else{
			$a = explode(' ',$right[0]);
			return $this->$a[0]($request,function($request){ 
				$request->addFeedback(COMMAND_OK);
				return 'CMD_OK';
			});
		}
	}
	
	private function _restCreate(\woo\controller\Request $request,$param){
		$item = $request->getProperty("item");
		$status = 'CMD_OK';
		if(isset($item) && (!isset($item["id"]) || substr($item["id"],0,2)=='c-')){
			if(!empty($param)){
				foreach($param as $p){
					if(empty($item[$p]))
						$item[$p] = "?$p";
				}
				$request->setProperty("item",$item);
			}
			if(isset($item['action'])){
				$cmd = $request->getProperty('cmd');
				$s = strtolower($cmd);
				if($item['action']=="{$s}_create"){
					$request->addFeedback(COMMAND_OK);
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback(COMMAND_OK);
			}
		}else{
			$request->addFeedback("ID is needed!");
			$result = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}
	
	/**
	 * REST create 
	 */
	function restCreate(\woo\controller\Request $request){
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		$status = 'CMD_OK';
		if(empty($right) || empty($right[1])){
			$request->addFeedback(COMMAND_OK);
			//if(1==$this->userShell($request)){
				//$status = $this->_restCreate($request,null);
			//}
		}else{
			$b = explode(' ',$right[1]['autoParam']);
			if(empty($right[1]['fn'])){
				if(1==$this->userShell($request)){
					$status = $this->_restCreate($request,$b);
				}
			}else{
				$a = explode(' ',$right[1]['fn']);
				if(1==$this->$a[0]($request,null)){ // 调用第一个验证函数
					if(count($a)==2){
						if(1==$this->$a[1]($request,null)){  // 调用第二个验证函数
							$status = $this->_restCreate($request,$b);
						}
					}else{
						$status = $this->_restCreate($request,$b);
					}
				}
			}
		}
		return $status;
	}
	
	private function _restUpdate(\woo\controller\Request $request){
		$item = $request->getProperty("item");
		$id = $request->getProperty("id");
		$status = 'CMD_OK';
		if(isset($item) && (isset($id) || isset($item["id"]))){
			if(isset($item['action'])){
				$cmd = $request->getProperty('cmd');
				$s = strtolower($cmd);
				if($item['action']=="{$s}_update"){
					$request->addFeedback(COMMAND_OK);
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback(COMMAND_OK);
			}
		}else{
			$request->addFeedback("ID is needed!");
			$status = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}

	function restUpdate(\woo\controller\Request $request){
		return $this->restOption($request,2);
	}
	
	private function _restDelete(\woo\controller\Request $request){
		$status = 'CMD_OK';
		$id = $request->getProperty("id");
		$action = $request->getProperty("action");
		if(isset($id) && is_numeric($id)){
			if(isset($action)){
				$cmd = $request->getProperty('cmd');
				$s = strtolower($cmd);
				if($action=="{$s}_delete"){
					$request->addFeedback(COMMAND_OK);
				}else{
					      $request->addFeedback("Request error!");
					      $status = 'CMD_INSUFFICIENT_DATA';
				}
			}else{
				$request->addFeedback(COMMAND_OK);
			}
		}else{
			$request->addFeedback("ID is needed!");
			$result = 'CMD_INSUFFICIENT_DATA';
		}
		return $status;
	}

	function restDelete(\woo\controller\Request $request){
		return $this->restOption($request,3);
	}
	
	function restOption(\woo\controller\Request $request,$index){
		$fun = array('_restGet','_restCreate','_restUpdate','_restDelete');
		$cmd = $request->getProperty('cmd');
		$right = self::validates($cmd);
		$status = 'CMD_INSUFFICIENT_DATA';
		if(empty($right) || empty($right[$index])){
			if(1==$this->userShell($request)){
				$status = $this->$fun[$index]($request);
			}
		}else{
			$a = explode(' ',$right[$index]);
			if(1==$this->$a[0]($request,NULL)){ // 调用第一个验证函数
				if(count($a)==2){
					if(1==$this->$a[1]($request,null)){  // 调用第二个验证函数
						$status = $this->$fun[$index]($request);
					}
				}else{
					$status = $this->$fun[$index]($request);
				}
			}
		}
		return $status;
	}
}

class DefaultCommand extends Command{
    function doExecute(\woo\controller\Request $request){
        $request->addFeedback("Welcome to WOO");
        include("view/P_main.php");
    }
}
?>
