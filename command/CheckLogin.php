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
require_once("domain/Custom.php");
require_once("domain/Employee.php");
//require_once("mapper/PersonCollection.php");
require_once("mapper/DomainObjectAssembler.php");
class CheckLogin extends Command{
	function doExecute(\woo\controller\Request $request){
		/**
		* 准备用户数据集合，并保存到白板($request)
		*/
		return $this->captchaShell($request,function($request){ 
			$state = 'CMD_INSUFFICIENT_DATA';
			$username = htmlentities($request->getProperty("username"),ENT_QUOTES,'UTF-8');
			$action = $request->getProperty("action");
			$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','username','pwd','active','email','nick','picture'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('username')->eq($username);
			$collection = $finder->find($idobj);
			if($collection->count()==1){
				$obj = $collection->current();
				if(!$obj){ // 记录是否存在
					$request->addFeedback("Invalid user name!");
				}else{
					//$request->log($obj->getId());
					$sub = "";
					$fields = array('id','userid');
					if($action=="employee_login"){
						$sub = "employee";
						$fields[] = 'myright';
					}else if($action=="custom_login")
						$sub = "custom";
					if(!empty($sub)){
						$factory = \woo\mapper\PersistenceFactory::getFactory($sub,$fields);
						$finder = new \woo\mapper\DomainObjectAssembler($factory);
						$idobj = $factory->getIdentityObject()->field('userid')->eq($obj->getId());
						$collection = $finder->find($idobj);
						if($collection->count()==1){
							//$request->log($obj->getId());
							$sobj = $collection->current();
							$request->setObject('person',$obj);
							$request->setObject('personex',$sobj);
							$request->addFeedback("Command Ok!");
							$state = 'CMD_OK';
						}else{
							$request->addFeedback("非系统用户！");
						}
					}else{
						$request->addFeedback("非法登录！");
					}
				}
			}else{
				$request->addFeedback("Invalid user name!");
			}
			return $state;
		});
	}
}
?>