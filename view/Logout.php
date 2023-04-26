<?php
/**
 * 客户登录验证 (ajax 调用)
 * @package command
 * @author  Liu xingming
 * @copyright 2012 Azuresky safe group
 * @return 如验证通过，返回包含用户信息的对象，否则返回指明验证失败信息的字符串
 */
namespace woo\controller;
require_once("controller/PageController.php");
require_once("base/SessionRegistry.php");

class LogoutController extends PageController{
    function process(){
      try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$obj = $request->getObject("person"); // 取要验证的客户数据
			if(!is_null($obj)){ // 如客户数据存在
				// 把客户信息保存到　session
				$session->delete('userid');
				$session->delete('usename');
				$session->delete('pwd');
				$session->delete('lasttime');
				$result["id"] = 0;
				$result["username"] = $obj->getUsername();
				
				// 返回客户信息
				echo json_encode($result);
			}else{
				throw new \woo\base\AppException($request->getFeedBackString());
			}
        }catch(\woo\base\AppException $e){
          $result["id"] = -1;
          $result["username"] = $e->getMessage();
					echo json_encode($result);
        }
    }
}

$controller = new LogoutController();
$controller->process();
?>