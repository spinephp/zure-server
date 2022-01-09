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
require_once("domain/Precisedevice.php");

class CheckDeviceController extends PageController{
	function process(){
		$result = array();
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$deviceid = htmlentities($request->getProperty("deviceid"),ENT_QUOTES,'UTF-8');
			$factory = \woo\mapper\PersistenceFactory::getFactory("precisedevice",array('id','deviceid','active','times'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('deviceid')->eq($deviceid);
			$collection = $finder->find($idobj);
            $percise = new \woo\domain\PreciseDevice(null);
            $active = 1;
			if($collection->count()==1){
				$obj = $collection->current();
                if(!is_null($obj)){ // 如客户数据存在
                    $active = $obj->getActive();
                    $percise->setId($obj->getid());
                    $percise->setLasttime(date("Y-m-d h:i:s"));
                    $percise->setTimes($obj->getTimes()+1);
                    $factory = \woo\mapper\PersistenceFactory::getFactory("precisedevice",array('id','lasttime','times'));
                    $finder = new \woo\mapper\DomainObjectAssembler($factory);
                    $finder->insert($percise);
                }
            } else {
                // 把登录时间和登录次数写入 person 表
                $percise->setId(-1);
                $percise->setDeviceid($deviceid);
                $percise->setActive($active);
                $percise->setFirsttime(date("Y-m-d h:i:s"));
                $percise->setLasttime(date("Y-m-d h:i:s"));
                $percise->setTimes(1);
                $factory = \woo\mapper\PersistenceFactory::getFactory("precisedevice",array('id','deviceid','firsttime','lasttime','times'));
                $finder = new \woo\mapper\DomainObjectAssembler($factory);
                $finder->insert($percise);
            }
            $result["id"] = $percise->getId();
            $result["active"] = $active;
            /**
            * token
            */
            if(!isset($_SESSION["token"])){
                $session->set("token",sha1(uniqid(mt_rand(),TRUE)));
            }
            $result["token"] = $session->get("token");
            $rsa = new \woo\base\Rsa();
            $session->set('rsa_private',$rsa->privateKey);

            $result['rsa'] = $rsa->publicKey;

            // 把客户信息保存到　session
            $session->set('id',$result['id']);
            // $session->set('lasttime',$obj->getLasttime());

            // 返回客户信息
            \woo\view\REST::response(json_encode($result));
		}catch(\woo\base\AppException $e){
			// $result["cid"] = isset($result["id"])? $result["id"]:-1;
			$result["id"] = -1;
			$result["error"] = $e->getMessage();
			\woo\view\REST::response(json_encode($result));
		}
	}
}

$controller = new CheckDeviceController();
$controller->process();
?>