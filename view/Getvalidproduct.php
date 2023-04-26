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
// require_once('view/resetPasswordEmail.php');
require_once("base/SessionRegistry.php");
require_once('domain/Cloudlevelbuy.php');
require_once('view/Buyverifyios.class.php');

class GetValidProductController extends BuyVaidiOSController{
	function process(){
        $plist = array(
            'com.yrr8.FieldLevel1001' => 'level',
            'com.yrr8.CloudLevel1001' => 'cloudlevel',
            'com.yrr8.CloudLevel1002' => 'cloudlevel',
            'com.yrr8.CloudLevel1003' => 'cloudlevel'
        );
		try{
            $result = array();
            $state = 'CMD_OK';
            $session = \woo\base\SessionRegistry::instance();
            $request = $this->getRequest();
            $uid = $request->getProperty('userid');
            $products = $request->getProperty("products");
            if(isset($products)) {
                $products = json_decode($products);
            }
            $table = "";
            foreach($plist as $key => $val) {
                if(in_array($key, $products)) {
                    $table = $val;
                    break;
                }
            }
            if($table == "") {
                $result["ok"] = false;
                $result["message"] = "商品"; 
                $result["products"] = array('product'=>$products[0],'state'=>-1);
            } elseif(isset($uid) && is_array($products)) {
                $cond = array(
                    array("field"=>"userid","value"=>$uid,"operator"=>"eq"),
                    array("field"=>"productid","value"=>$products,"operator"=>"in")
                );
                $m = new \woo\mapper\FinalAssembler($table."buy",array('id','userid',"productid",'content',"buytime",'expirestime',"state"),$cond);
                $objs = $m->find();
                $cnt = count($objs);
                if($cnt == 0) {
                    $result["ok"] = false;
                    $result["message"] = "商品未购买"; 
                    $result["products"] = array('product'=>$products[0],'state'=>-1);
                } elseif(isset($last['expirestime'])) {
                    $last = $objs[$cnt-1];
                    $curTime = Time();
                    $expeiresTimeMs = $last['expirestime'];
                    $expeiresTime = substr($expeiresTimeMs,0,-3);
                    $content = json_decode($last['content'],true);
                    $oldNotfy = $content['notify'];
                    if($curTime - (int)$expeiresTime >= 0) { // 
                        if($last['state'] != 4) {
                            $receipt_data = $content["receipt_data"];
                            $validate = $this->validate_apple_pay($receipt_data); 
                            if(!$validate['status']) { //验证不通过
                                $result = array(
                                    'ok'=>false,
                                    'message'=>$validate['message']);//,
                                    // 'products'=>array());
                                echo json_encode($result);
                                exit(0);
                            }
                            $notify = $validate['data'];
                            $productId = $notify['product_id'];
                            $expiresTimeMs = $notify['expires_date_ms'];
                            $expeiresTime = substr($expiresTimeMs,0,-3);
                            $content = array(
                                // 'appleTransCnt'=>0, //$transResult['appleTransCnt'],
                                'notify'=>$notify,
                                'receipt_data'=>$receipt_data);
                                    
                            $name = "\\woo\\domain\\".ucfirst($table."buy");
                            $buy = new $name(null);
                            $buy->setId($last['id']);
                            $buy->setUserid($uid);
                            // $buy->setProductid($productId);
                            // $buy->setTransactionid($transId);
                            // $buy->setOrigintransid($originalTransId);
                            $buy->setContent(json_encode($content));
                            // $buy->SetBuytime($purchaseDate);
                            $buy->setExpirestime($expiresTimeMs);
                            if($notify['is_trial_period'] == 'true') {
                                $buy->setState(0);
                                $result["ok"] = true;
                                $result["message"] = "ok"; 
                                $result["products"] = array('product'=>$last['productid'],'state'=>0);
                            } else {
                                if($curTime - (int)$expeiresTime >= 0) {
                                    $buy->setState(4);
                                    $result = array('ok'=>false,'message'=>'订单失败','products'=>array('product'=>$last['productid'],'state'=>3));
                                } else {
                                    $buy->setState(0);
                                    $result["ok"] = true;
                                    $result["message"] = "ok"; 
                                    $result["products"] = array('product'=>$last['productid'],'state'=>0);
                                }
                            }
                            $fields = array('id','userid','transactionid','origintransid','productid','content','buytime','expirestime','state');
                            $factory = \woo\mapper\PersistenceFactory::getFactory($table."buy",$fields);
                            $finder = new \woo\mapper\DomainObjectAssembler($factory);
                            $finder->insert($buy);
                            if($buy->getId()<1){
                                // $eventSystem->add_error('苹果端回调-result',$request_uri,'订单处理出错');
                                $result['ok'] = false;
                                $result['message'] = $result['message'].' - 写入订单失败';
                            }
                        } else {
                            $result = array('ok'=>false,'message'=>'订单失败','products'=>array('product'=>$last['productid'],'state'=>3));
                        }
                    } else {
                        $result["ok"] = true;
                        $result["message"] = "ok"; 
                        $result["products"] = array('product'=>$last['productid'],'state'=>0);
                    }
                } else {
                    $result["ok"] = true;
                    $result["message"] = "ok"; 
                    $result["products"] = array('product'=>$products[0],'state'=>0);
                }
            } else {
                $result["ok"] = false;
                $result["message"] = "参数出错"; 
                // $result["products"] = array();
            }
			echo json_encode($result);
        }catch(\woo\base\AppException $e){
			$result["status"] = false;
			$result["message"] = $e->getMessage();
            // $result["products"] = array();
			echo json_encode($result);
        }
    }
}
$controller = new GetValidProductController();
$controller->process();
?>
