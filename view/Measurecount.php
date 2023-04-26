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
require_once("domain/Measure.php");
// require_once("domain/Measuremessage.php");

class MeasureCountController extends PageController{
	function process(){
		$result = array();
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$device = htmlentities($request->getProperty("device"),ENT_QUOTES,'UTF-8');
			$factory = \woo\mapper\PersistenceFactory::getFactory("measure",array('id','device','lasttime','times','secords'));
			$finder = new \woo\mapper\DomainObjectAssembler($factory);
			$idobj = $factory->getIdentityObject()->field('device')->eq($device);
			$collection = $finder->find($idobj);
            $measure= new \woo\domain\Measure(null);
			if($collection->count()==1){
				$obj = $collection->current();
                if(!is_null($obj)){ // 如客户数据存在
                    $time1 = strtotime($obj->getLasttime());
                    $data= date("Y-m-d H:i:s");
                    $time2 = strtotime($data);
                    $times = $obj->getTimes()+1;
                    $secords = $obj->getSecords()+$time2-$time1;
                    
                    $measure->setId($obj->getid());
                    $measure->setTimes($times);
                    $measure->setSecords($secords);
                    $factory = \woo\mapper\PersistenceFactory::getFactory("measure",array('id','times','secords'));
                    $finder = new \woo\mapper\DomainObjectAssembler($factory);
                    $finder->insert($measure);
                }
            }
            $result["id"] = $measure->getId();

            // 返回客户信息
            \woo\view\REST::response(json_encode($result));
		}catch(\woo\base\AppException $e){
			$result["id"] = -1;
			$result["error"] = $e->getMessage();
			\woo\view\REST::response(json_encode($result));
		}
	}
}

$controller = new MeasureCountController();
$controller->process();
?>