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
require_once("domain/Measureversion.php");
require_once("mapper/DomainObjectAssembler.php");
class MeasureLatestVersion extends Command{
	function doExecute(\woo\controller\Request $request){
		/**
		* 准备用户数据集合，并保存到白板($request)
		*/
		// return $this->captchaShell($request,function($request){ 
			$state = 'CMD_INSUFFICIENT_DATA';
			$sysVersion = \woo\domain\DomainObject::checkVersion($request->getProperty("sysMinVersion"));
			$curVersion = \woo\domain\DomainObject::checkVersion($request->getProperty("version"));
			$factory = \woo\mapper\PersistenceFactory::getFactory("measureversion",array('id', 'version', 'sysversion','overview','overview_en' ,'content', 'content_en','address', 'size','sha256','mandatory'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('sysversion')->vle($sysVersion)->field('version')->vgt($curVersion);
			$collection = $finder->find($idobj);
			// $collection = $finder->latest();
			if($collection->count()>0){
				$obj = $collection->latest();
				// $obj = $collection->current();
				if(!$obj){ // 记录是否存在
					$request->addFeedback("Invalid user name!");
				}else{
                    $request->setObject('measureversion',$obj);
                    $request->addFeedback("Command Ok!");
                    $state = 'CMD_OK';
				}
			}else{
				$request->addFeedback("Invalid version or system version !");
			}
			// $request->log('MeasureLatestVersion: '.$state.$request->getFeedBackString());
			return $state;
		// });
	}
}
?>