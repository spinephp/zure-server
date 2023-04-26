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
require_once('view/resetPasswordEmail.php');
require_once("base/SessionRegistry.php");
require_once('domain/Cloudlevelbuy".php');

class IsValidProductController extends PageController{
	function process(){
		try{
            $result = "";
            $state = 'CMD_OK';
            $session = \woo\base\SessionRegistry::instance();
            $request = $this->getRequest();
            $userid = $request->getProperty('userid');
            $products = $request->getProperty("products");
            $table = "cloudlevelbuy";
            if (isset($userid) && isset($products) && is_array(products)) {
                $cond = array(array(
                    "field"=>"userid","value"=>$userd,"operator"=>"eq"),
                    "field"=>"productid","value"=>$products,"operator"=>"in"
                );
                $m = new \woo\mapper\FinalAssembler($table,array("productid","time","state"),$cond);
                $objs = $m->find();
                $cnt = count($objs);
                if($cnt>0 && $objs[$cnt-1]["state"]<2){ // state: 0-首次购买 1-已续费 2-己取消 3-已过期
                    $result["ok"] = true;
                    $result["message"] = "商品已购买";
                    $result["products"] = array($objs[$cnt-1]["productid"]);
                } else {
                    $result["ok"] = false;
                    $result["message"] = "商品未购买"; 
                    $result["products"] = array();
                }
            } else {
                $result["ok"] = false;
                $result["message"] = "参数出错"; 
                $result["products"] = array();
            }
			echo json_encode($result);
        }catch(\woo\base\AppException $e){
			$result["status"] = false;
			$result["message"] = $e->getMessage();
            $result["products"] = array();
			echo json_encode($result);
        }
    }
}
$controller = new IsValidProductController();
$controller->process();
?>
