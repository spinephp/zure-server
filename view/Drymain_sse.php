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

class DryMainController extends PageController{
    function process(){
      try{
	      $session = \woo\base\SessionRegistry::instance();
        $request = $this->getRequest();
			  $starttime = $request->getProperty("starttime"); // 取要验证的客户数据
			  $lineno = $request->getProperty("lineno"); // 取要验证的客户数据
                // 把登录时间和登录次数写入 person 表
			    $dry = new \woo\domain\Drymain(null);
                $dry->setStarttime($starttime);
                $dry->setLineno($lineno);
			  $factory = \woo\mapper\PersistenceFactory::getFactory("drymain",array('id','starttime','lineno'));
			  $finder = new \woo\mapper\DomainObjectAssembler($factory);
			  $finder->insert($dry);
			$result["id"] = $dry->getId();
			$session->set('dryid',$result['id']);
 			echo json_encode($result);
               
        }catch(\woo\base\AppException $e){
          $result["id"] = -1;
          $result["username"] = $e->getMessage();
					echo json_encode($result);
        }
    }
}

$controller = new DryMainController();
$controller->process();
?>