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

class MeasureLatestVersionController extends PageController{
    function process(){
      try{
        $session = \woo\base\SessionRegistry::instance();
        $request = $this->getRequest();
        // $request->log($request->getFeedBackString());
        $obj = $request->getObject("measureversion"); // 取要验证的客户数据
        if(!is_null($obj)){ // 如客户数据存在
          $language=(int)$request->getProperty("language");
          $overviews = array($obj->getOverview_en(),$obj->getOverview());
          $contents = array($obj->getContent_en(),$obj->getContent());
          $result["id"] = $obj->getId();
          $result["release"] = array(
                      'version'=>$obj->getVersion(),
                      'sysversion'=>$obj->getSysversion(),
                      'overview'=>$overviews[$language],
                      'content'=>$contents[$language],
                      'address'=>$obj->getAddress(),
                      'size'=>$obj->getSize(),
                      'sha256'=>$obj->getSha256(),
                      'mandatory'=>$obj->getMandatory()) ;
          
          // 返回客户信息
          echo json_encode($result);
        }else{
          throw new \woo\base\AppException($request->getFeedBackString());
        }
      }catch(\woo\base\AppException $e){
        $result["id"] = -1;
        $result["version"] = $e->getMessage();
        echo json_encode($result);
      }
    }
}

$controller = new MeasureLatestVersionController();
$controller->process();
?>