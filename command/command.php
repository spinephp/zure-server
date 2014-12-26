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
	
  function restGet(\woo\controller\Request $request){ 
    $request->addFeedback("Command Ok!");
		return 'CMD_OK';
	}

  function restCreate(\woo\controller\Request $request){
		return $this->userShell($request,function($request){
			$item = $request->getProperty("item");
			$result = 'CMD_OK';
			if(isset($item) && !isset($item["id"])){
				$request->addFeedback("Command Ok!");
			}else{
				$request->addFeedback("ID is needed!");
				$result = 'CMD_INSUFFICIENT_DATA';
			}
			return $result;
		});
	}

  function restUpdate(\woo\controller\Request $request){
		return $this->userShell($request,function($request){
				$item = $request->getProperty("item");
				$result = 'CMD_OK';
				if(isset($item) && isset($item["id"])){
					$request->addFeedback("Command Ok!");
				}else{
					$request->addFeedback("ID is needed!");
					$result = 'CMD_INSUFFICIENT_DATA';
				}
				return $result;
		});
	}

  function restDelete(\woo\controller\Request $request){
		return $this->userShell($request,function($request){
				$result = 'CMD_OK';
				$id = $request->getProperty("id");
				if(!empty($id)){
					$request->addFeedback("Command Ok!");
				}else{
					$request->addFeedback("Invalid paramter!");
					$result = 'CMD_INSUFFICIENT_DATA';
				}
				return $result;
		});
	}

}

class DefaultCommand extends Command{
    function doExecute(\woo\controller\Request $request){
        $request->addFeedback("Welcome to WOO");
        include("view/main.php");
    }
}
?>