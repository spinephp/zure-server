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
//require_once("mapper/PersonCollection.php");
require_once("mapper/DomainObjectAssembler.php");

class IsLogin extends Command{
	function doExecute(\woo\controller\Request $request){
		$session = \woo\base\SessionRegistry::instance();
		$firstlogin = $session->get("firstlogin");
		if(is_null($firstlogin)) $firstlogin = true;
		$result['login'] = $firstlogin;
		if($firstlogin){
			$rsa = new \woo\base\Rsa();
			$session->set('rsa_private',$rsa->privateKey);

			$result['token'] = $rsa->publicKey;
			$result['sessionid'] = session_id();

			$session->set('firstlogin',false);
		}
		echo json_encode(array($result));
	}
}
?>