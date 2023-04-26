<?php
/**
* 定义客户登录命令
* 流程如下：
* 1. 检查验证码是否正确，如正确执行下一步，否则转 5
* 2. 在数据库中查找与用户名和密码对应的记录，如记录存在执行下一步，否则转 4
* 3. 把记录和反馈信息保存到白板，返回 CMD_OK
* 4. 把用户名和密码错误信息保存到白板，返回 CMD_INSUFFICIENT_DATA
* 5. 把验证码错误信息保存到白板，返回 CMD_INSUFFICIENT_DATA
*
* @package command
* @author  Liu xingming
* @copyright 2012 Azuresky safe group
*/

namespace woo\command;

require_once("command/command.php");
require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("domain/Person.php");
class ResetPassword extends Command{
	function doExecute(\woo\controller\Request $request){
    		$result = $this->captchaShell($request);
    		if($result==1)
    			$result = $this->safeShell($request,'_resetPWD');
    		return $result;
	}

	function _resetPWD(\woo\controller\Request $request){
		$state = 'CMD_OK';
		$session = \woo\base\SessionRegistry::instance();
		$username = $request->getProperty("username");
		$email = $request->getProperty("email");
		$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','username','email'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('username')->eq($username)->field('email')->eq($email);
		$collection = $finder->find($idobj);
		$obj = $collection->current();
		if(!$obj){ // 记录是否存在
			$request->addFeedback("Invalid username or email！");
			$state = 'CMD_INSUFFICIENT_DATA';
		}else{
			$request->setObject('person',$obj);
			$request->addFeedback(COMMAND_OK);
		}
		return self::statuses($state);
	}
}
?>