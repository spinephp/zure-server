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
require_once("domain/Person.php");
require_once("mapper/PersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");
class ActiveAccount extends Command{
  function doExecute(\woo\controller\Request $request){
	 /**
		* 准备用户数据集合，并保存到白板($request)
		*/
			  $state = 'CMD_INSUFFICIENT_DATA';
        $hash = $request->getProperty("verify");
        if(preg_match("/^[a-fA-F0-9]{32}$/",$hash)){
				  $factory = \woo\mapper\PersistenceFactory::getFactory("person",array("id","hash","active","lasttime"));
				  $finder = new \woo\mapper\DomainObjectAssembler($factory);
				  $idobj = $factory->getIdentityObject()->field('hash')->eq($hash);
				  $collection = $finder->find($idobj);
          if($collection->count()==1){
				    $obj = $collection->current();
				    if(!$obj){ // 记录是否存在
					    $request->addFeedback("Invalid hash value！");
				    }else{
              if($obj->getActive()=='N' && $obj->getHash()==$hash){
                $now = strtotime(date('Y-m-d H:i:s'));
                $registertime = strtotime($obj->getLasttime());
                if($now-$registertime>2*24*60*60)
					        $request->addFeedback("Link expired！");
                else{
                    $request->setObject("id",$obj->getId());
				            $request->addFeedback("Command Ok!");
                    $state = 'CMD_OK';
                }
              }else{
					        $request->addFeedback("Account has been actived！");
              }
            }
          }else{
					  $request->addFeedback("Invalid hash value！");
          }
        }else{
					  $request->addFeedback("Invalid hash value！");
          }
		    return $state;
	}
}
?>