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
require_once("domain/Level.php");
require_once("domain/Levelmessage.php");

class LevelDeviceController extends PageController{
	function process(){
		$result = array();
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$device = htmlentities($request->getProperty("device"),ENT_QUOTES,'UTF-8');
			$version = htmlentities($request->getProperty("version"),ENT_QUOTES,'UTF-8');
			$factory = \woo\mapper\PersistenceFactory::getFactory("level",array('id','name','password','device','version','state'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('device')->eq($device);
			$collection = $finder->find($idobj);
            $level = new \woo\domain\Level(null);
            $state = 1;
            $name = "M-01";
            $password = "Li@12345";
			if($collection->count()==1){
				$obj = $collection->current();
                if(!is_null($obj)){ // 如客户数据存在
                    $state = $obj->getState();
                    $name = $obj->getName();
                    $password = $obj->getPassword();
                    $level->setId($obj->getid());
                    $level->setLasttime(date("Y-m-d h:i:s"));
                    $level->setVersion($version);
                    $factory = \woo\mapper\PersistenceFactory::getFactory("level",array('id','lasttime'));
                    $finder = new \woo\mapper\DomainObjectAssembler($factory);
                    $finder->insert($level);
                }
            } else {
                // 把登录时间和登录次数写入 person 表
                $level->setId(-1);
                $level->setName($name);
                $level->setPassword($password);
                $level->setDevice($device);
                $level->setFirsttime(date("Y-m-d h:i:s"));
                $level->setLasttime(date("Y-m-d h:i:s"));
                $level->setState($state);
                $level->setVersion($version);
                $factory = \woo\mapper\PersistenceFactory::getFactory("level",array('id','device','firsttime','lasttime','version','state'));
                $finder = new \woo\mapper\DomainObjectAssembler($factory);
                $finder->insert($level);
            }
            $result["id"] = $level->getId();
            $result["name"] = $name;
            $result["password"] = $password;
            $result["state"] = $state;
            /**
            * token
            */
            if(!isset($_SESSION["token"])){
                $session->set("token",sha1(uniqid(mt_rand(),TRUE)));
            }
            $result["token"] = $session->get("token");
            $rsa = new \woo\base\Rsa();
            $session->set('rsa_private',$rsa->privateKey);

            // $result['rsa'] = $rsa->publicKey;

            // 把客户信息保存到　session
            $session->set('userid',$result['id']);
            // $session->set('lasttime',$obj->getLasttime());

			$factory1 = \woo\mapper\PersistenceFactory::getFactory("levelmessage",array('type','title','title_en','body','body_en','params','state'));
			$finder1 = new \woo\mapper\DomainObjectAssembler($factory1);
			$idobj1 = $factory1->getIdentityObject()->field('state')->ge(1);
            $collection = $finder1->find($idobj1);
            $msgs = array();
            $msg = array();
            foreach($collection as $m) {
                $msg['type'] = $m->getType();
                $msg['titles'] = $m->getTitles();
                $msg['bodys'] = $m->getBodys();
                $msg['params'] = $m->getParams();
                $msg['state'] = $m->getState();
                $msgs[] = $msg;
            }
            // 返回客户信息
            \woo\view\REST::response(json_encode(array("user"=>$result,"messages"=>$msgs))
        );
		}catch(\woo\base\AppException $e){
			// $result["cid"] = isset($result["id"])? $result["id"]:-1;
			$result["id"] = -1;
			$result["error"] = $e->getMessage();
			\woo\view\REST::response(json_encode($result));
		}
	}
}

$controller = new LevelDeviceController();
$controller->process();
?>