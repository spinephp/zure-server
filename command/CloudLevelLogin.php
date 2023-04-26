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
require_once("domain/Cloudlevel.php");
require_once("mapper/DomainObjectAssembler.php");
class CloudLevelLogin extends Command{
	function doExecute(\woo\controller\Request $request){
		/**
		* 准备用户数据集合，并保存到白板($request)
		*/
		// return $this->captchaShell($request,function($request){ 
			$state = 'CMD_INSUFFICIENT_DATA';
			$email = htmlentities($request->getProperty("email"),ENT_QUOTES,'UTF-8');
			$factory = \woo\mapper\PersistenceFactory::getFactory("cloudlevel",array('id','email','password','state'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('email')->eq($email);
			$collection = $finder->find($idobj);
			if($collection->count()==1){
				$obj = $collection->current();
				if(!$obj){ // 记录是否存在
					$request->addFeedback("Invalid user name!");
				}else{
                    $request->setObject('cloudlevel',$obj);
                    $request->addFeedback("Command Ok!");
                    $state = 'CMD_OK';
				}
			}else{
				$request->addFeedback("Invalid user name!");
			}
			return $state;
		// });
	}
}
?>