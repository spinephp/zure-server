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

class GetLogIdController extends PageController{
	function process(){
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$cmdStatus = $request->getFeedbackString();
			if($cmdStatus!="Command Ok!")
				throw new \woo\base\AppException($cmdStatus);
			$userid=$session->get('userid');
			if(empty($userid))
				throw new \woo\base\AppException("Not logged");
			echo json_encode(array('id'=>$userid));
		}catch(\woo\base\AppException $e){
			echo json_encode(array("id"=>-1,"error"=>$e->getMessage()));
		}
	}
}

$controller = new GetLogIdController();
$controller->process();
?>