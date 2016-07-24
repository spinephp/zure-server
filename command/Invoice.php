<?php
/**
* 定义查看合同命令
*
* @package command
* @author  Liu xingming
* @copyright 2014 Azuresky safe group
*/

namespace woo\command;

require_once("command/command.php");
require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("base/const.right.php");
require_once("domain/Person.php");
require_once("mapper/DomainObjectAssembler.php");
class Invoice extends Command{
  function doExecute(\woo\controller\Request $request){
	 /**
		* 准备用户数据集合，并保存到白板($request)
		*/
    return $this->safeShell($request,'restGet');
	}
  
  function restGet(\woo\controller\Request $request){
      return $this->userShell($request,function($request,$userid){ 
        $state = 'CMD_INSUFFICIENT_DATA';
        $orderid = $request->getProperty("orderid");
        $fields = array('id');
				$factory = \woo\mapper\PersistenceFactory::getFactory("person",$fields);
				$finder = new \woo\mapper\DomainObjectAssembler($factory);
				$idobj = $factory->getIdentityObject()->field('id')->eq($userid);
				$collection = $finder->find($idobj);
        if($collection->count()==1){
				  $obj = $collection->current();
				  if(!$obj){ // 记录是否存在
					  $request->addFeedback("用户名不存在！");
				  }else{
            $sub = "";
            $fields[] = 'userid';
            if($obj->isCustom())
              $sub = "custom";
            else{
              $sub = "employee";
              $fields[] = 'myright';
            }
				    $factory = \woo\mapper\PersistenceFactory::getFactory($sub,$fields);
				    $finder = new \woo\mapper\DomainObjectAssembler($factory);
				    $idobj = $factory->getIdentityObject()->field('userid')->eq($obj->getId());
				    $collection = $finder->find($idobj);
            if($collection->count()==1){
              $sobj = $collection->current();
              if($sub=="custom"){ // 客户查看合同
				        $factory = \woo\mapper\PersistenceFactory::getFactory("order",array('id','userid'));
				        $finder = new \woo\mapper\DomainObjectAssembler($factory);
				        $idobj = $factory->getIdentityObject()->field('id')->eq($orderid)->field('userid')->eq($userid);
				        $collection = $finder->find($idobj);
                if($collection->count()==1){
				          $request->addFeedback("Command Ok!");
                  $state = 'CMD_OK';
                }else{
					        $request->addFeedback("当前客户无此订单！");
                }
              }else{ // 雇员查看合同
                $right = $sobj->getMyright();
                if(($right & RIGHT_ORDER_SHOW) || ($right & RIGHT_ORDER_EDIT)){
				          $request->addFeedback("Command Ok!");
                  $state = 'CMD_OK';
                }else{
					        $request->addFeedback("无此权限！");
                }
              }
            }else{
					    $request->addFeedback("非系统用户！");
            }
          }
        }else{
					$request->addFeedback("用户不存在！");
        }
		    return self::statuses($state);
      });
  }
}
?>