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
require_once("view/REST.php");

class CheckLoginController extends PageController{
	function process(){
		$result = array();
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$obj = $request->getObject("person"); // 取要验证的客户数据
			$sobj = $request->getObject("personex"); // 取要验证的客户数据
			$action = $request->getProperty('action');
			if(!is_null($obj)){ // 如客户数据存在
				if($obj->isPassword($request->getProperty('pwd'))){ // 如密码正确
					$result['id'] = $obj->getid();
					$result['name'] = $obj->getUsername();
					$result["active"] = $obj->getActive();
					$result["email"] = $obj->getEmail();
					if($obj->getActive()=='Y'){ // 如果客户已激活
						$result["picture"] = $obj->getPicture();
						$result["state"] = 0;
						if($result["id"]){
							if($action=="custom_login"){
								// 把登录时间和登录次数写入 person 表
								$person = new \woo\domain\Person(null);
								$person->setId($result["id"]);
								$person->setLasttime(date("Y-m-d h:i:s"));
								$person->setTimes($obj->getTimes()+1);
								$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','lasttime','times'));
								$finder = new \woo\mapper\DomainObjectAssembler($factory);
								$finder->insert($person);

								$result["state"] = 1;
							}else{
								$result["state"] = $sobj->getMyright();
								/**
								* token
								*/
								if(!isset($_SESSION["token"])){
									$session->set("token",sha1(uniqid(mt_rand(),TRUE)));
								}
								$result["token"] = $session->get("token");
							}
						}

						// 把客户信息保存到　session
						$session->set('userid',$result['id']);
						$session->set('usename',$result['name']);
						$session->set('pwd',$obj->getPwd());
						$session->set('lasttime',$obj->getLasttime());

						// 返回客户信息
						\woo\view\REST::response(json_encode($result));
					}else{
						throw new \woo\base\AppException("Account not actived!");
					}
				}else{
					throw new \woo\base\AppException("Password error!");
				}
			}else{
				throw new \woo\base\AppException($request->getFeedBackString());
			}
		}catch(\woo\base\AppException $e){
			$result["cid"] = isset($result["id"])? $result["id"]:-1;
			$result["id"] = -1;
			$result["error"] = $e->getMessage();
			\woo\view\REST::response(json_encode($result));
		}
	}
}

$controller = new CheckLoginController();
$controller->process();
?>