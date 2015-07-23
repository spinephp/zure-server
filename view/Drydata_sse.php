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

class DryDataController extends PageController{
    function process(){
      try{
	      $session = \woo\base\SessionRegistry::instance();
        $request = $this->getRequest();
			  $time = $request->getProperty("time"); // 取要验证的客户数据
			  $temperature = $request->getProperty("temperature"); // 取要验证的客户数据
			  $mode = $request->getProperty("mode"); // 取要验证的客户数据
			  $settingtemperature = $request->getProperty("settingtemperature"); // 取要验证的客户数据
			  $mainid = $session->get("dryid");
                // 把登录时间和登录次数写入 person 表
			    $dry = new \woo\domain\Drydata(null);
                $dry->setTime($time);
                $dry->setTemperature($temperature);
                $dry->setSettingtemperature($settingtemperature);
                $dry->setMode($mode);
                $dry->setMainid($mainid);
			  $factory = \woo\mapper\PersistenceFactory::getFactory("drydata",array('id','time','settingtemperature','temperature','mode','mainid'));
			  $finder = new \woo\mapper\DomainObjectAssembler($factory);
			  $finder->insert($dry);
			$result["id"] = $dry->getId();
 			echo json_encode($result);
               
        }catch(\woo\base\AppException $e){
          $result["id"] = -1;
          $result["username"] = $e->getMessage();
					echo json_encode($result);
        }
    }
}

$controller = new DryDataController();
$controller->process();
?>