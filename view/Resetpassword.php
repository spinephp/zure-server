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
require_once('"view/resetPasswordEmail.php"');
require_once("base/SessionRegistry.php");

class ResetPasswordController extends PageController{
	function process(){
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$obj = $request->getObject("person"); // 取要验证的客户数据
			$action = $request->getProperty('action');
			$username = $obj->getUsername();
			$email = $obj->getEmail();
			$type = $request->getProperty("type");
			$language = (int)$request->getProperty("language");
			if(!is_null($obj)){ // 如客户数据存在
				$result['id'] = $obj->getId();
				if($result["id"]){
					if($action=="custom_resetPassword"){
						// 创建唯一标识符
						$hash = md5(uniqid(rand(1,1)));
						$now = date('Y-m-d h:i:s');
						// 把登录时间和登录次数写入 person 表
						$person = new \woo\domain\Person(null);
						$person->setId($result["id"]);
						$person->setHash($hash);
						$person->setLasttime($now);
						$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','hash'));
						$finder = new \woo\mapper\DomainObjectAssembler($factory);
						$finder->insert($person);
			
						$mail = new resetPasswordMail($email,$username,$hash,$type);
						$mail->setLanguage($language);
						$mail->send();
						echo json_encode($result);
					}
				}
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

$controller = new ResetPasswordController();
$controller->process();
?>