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
//require_once('view/resetPasswordEmail.php');
require_once("base/SessionRegistry.php");
// require_once('domain/Level.php');

class BuyVaidiOSController extends PageController {
    //IOS内购配置
    $applepay = array(
        'levelbuy' => array(
           'bundle_id'     => 'com.yrr8.FieldLevel', //应用唯一标识
           'products' => ['com.yrr8.FieldLevel1001'], //内购商品
            'apple_secret'  => '91ba676daa8146cebe28f9a2a734c8c0' //应用密钥,在itunes中获取
        ),
        'cloudlevelbuy' => array(
           'bundle_id'     => 'com.yrr8.CloudLevel', //应用唯一标识
           'products' => ['com.yrr8.CloudLevel1001','com.yrr8.CloudLevel1002','com.yrr8.CloudLevel1003'], //内购商品
           'apple_secret'  => '1868c2da3302475db479c18c5452af51' //应用密钥,在itunes中获取
        )
    ]);
    
    function process(){
		try{
            $state = 'CMD_OK';
            $session = \woo\base\SessionRegistry::instance();
            $request = $this->getRequest();
            $item = $request->getProperty('item');
            $receipt_data = $item['receipt']; // 凭证
            // $transactionid = $item['transactionid']; // 交易流水
            $sendbox = $item['sendbox'];

            //进行验证通过的逻辑处理 
            $table = "";
            $table0 = "";
            $sep = DIRECTORY_SEPARATOR;
            foreach($item as $key=>$value) {
                if(in_array($key,$applepay)) {
                    $table = $key;
                    $table0 = substr($key, 0, -3);
                    $target = ucfirst($key);
                    $target0 = ucfirst($table0);
                    include_once("domain/$target.php");
                    include_once("domain/$target0.php");
                    break;
                }
            }
            $secret = $applepay[$table]['apple_secret'];
            //待验证数据
            $validate = $this->validate_apple_pay($item['receipt'], $secret); 
            if(!$validate['status']) { //验证不通过
                $result = array(
                    'ok'=>false,
                    'message'=>$validate['message']);//,
                    // 'products'=>array());
                echo json_encode($result);
                exit(0);
            }
            /**
             * quantity:"1"
             * product_id:"com.yrr8.CloudLevel1002"
             * transaction_id:"1000000718341627"
             * original_transaction_id:"1000000715710519"
             * purchase_date:"2020-09-14 15:14:19 Etc/GMT"
             * purchase_date_ms:"1600096459000"
             * purchase_date_pst:"2020-09-14 08:14:19 America/Los_Angeles"
             * original_purchase_date:"2020-09-07 13:48:41 Etc/GMT"
             * original_purchase_date_ms:"1599486521000"
             * original_purchase_date_pst:"2020-09-07 06:48:41 America/Los_Angeles"
             * expires_date:"2020-09-14 15:19:19 Etc/GMT"
             * expires_date_ms:"1600096759000"
             * expires_date_pst:"2020-09-14 08:19:19 America/Los_Angeles"
             * web_order_line_item_id:"1000000055661627"
             * is_trial_period:"false"
             * is_in_intro_offer_period:"false"
             * subscription_group_identifier:"20679171"
            */
            $notify = $validate['data'];
            $bundleId = $applepay[$table]['bundle_id'];
            $products = $applepay[$table]['products'];
            $bunle_id = $notify['bundle_id'];
            $product_id = $notify['product_id'];
            $uid = $item[$table]["id"];
            $cond0 = array(array("field"=>"id","value"=>$uid,"operator"=>"eq"));
			$m0 = new \woo\mapper\FinalAssembler($table0,array("id"),$cond0);
			$objs0 = $m0->find();
            if(count($objs0)>0 && $bundle_id==$bundleId && in_array($product_id,$products)){ // 用户存在
                $id = -1;
                $transResult = $this->check_apple_trans($table,$notify,$receipt_data);
                
                $state = 0;
                $expiresTime = null;
                if(isset($notify['expires_date_ms'])){
                    $expiresTime = substr($notify['expires_date_ms'],0,-3);
                    $time = time();
                    if($time - (int)$expiresTime >=0) {
                        $state = 3;
                        if(isset($validate['sdata']['pending_renewal_info'][0]['is_in_billing_retry_period'])){
                            $billRetry = $validate['sdata']['pending_renewal_info'][0]['is_in_billing_retry_period'];
                            if($billRetry != '0') {
                                $state = 1;
                            }
                        } 
                    }
                }
                if($transResult['status']<=0){
                    $id = $transResult['id'];
                    // $eventSystem->add_error('苹果端回调-result',$request_uri,'交易号已经出现过了');
                    // echo json_encode(array('ok'=>true,'message'=>'交易号已经出现过了'));
                    // exit(0);
                }

                // 处理订单数据
                $buyerInfo = $validate['sandbox'];  // 1 沙盒数据 0 正式
//                $productId = $notify['product_id']; // 订单类型
                $transId = $notify['transaction_id'];   // 交易的标识
                $originalTransId = $notify['original_transaction_id'];  // 原始交易ID
                // $transTime = $this->toTimeZone($notify['purchase_date']);  // 购买时间
                // $is_trial_period = $notify['is_trial_period'] == 'false' ? 0 : 1; //是否首次购买
                $purchaseDate = str_replace(' America/Los_Angeles','',$notify['purchase_date_pst']);
                $content = array(
                    // 'appleTransCnt'=> $id!=-1? $transResult['appleTransCnt']:0,
                    'notify'=>$validate['sdata'];,
                    'receipt_data'=>$receipt_data);
                $contentJson = json_encode($content);
                // $pay_detail = $this->pay_detail[$reward]; // 购买畅读卡
                // $products = array_column($pay_detail,null,'expend_identifier');
                // $products = $products[$productId];
                // $total_fee = $products['pay']*100; // 分
                $type = 3; // 苹果内购支付
                if($buyerInfo == 1){
                    $type = 6;//沙盒模式
                }

                // 写入订单（这个其实可以在IOS发起支付的时候请求服务端，先生成订单，并返回订单号）
                $orderId = 'ios_a'.$uid.date("mdHis").rand(2000,8000);
                $name = "\\woo\\domain\\".ucfirst($table);
                $buy = new $name(null);
                if($id != -1) {
                    $buy->setId($id);
                }
                $buy->setUserid($uid);
                $buy->setProductid($product_id);
                $buy->setTransactionid($transId);
                $buy->setOrigintransid($originalTransId);
                $buy->setContent($contentJson);
                $buy->SetBuytime($purchaseDate);
                if(isset($notify['expires_date_ms'])){
                    $buy->setExpirestime($notify['expires_date_ms']);
                }
                $buy->setState($state);
                $fields = array('id','userid','transactionid','origintransid','productid','content','buytime','expeirestime','state');
                $factory = \woo\mapper\PersistenceFactory::getFactory($table,$fields);
                $finder = new \woo\mapper\DomainObjectAssembler($factory);
                $finder->insert($buy);
                if($buy->getId()<1){
                    // $eventSystem->add_error('苹果端回调-result',$request_uri,'订单处理出错');
                    echo json_encode(array('ok'=>false,'message'=>'写入订单失败','products'=>array('product'=>$productId,'state'=>$state)));
                }

                // 处理订单 
                // $rs = 1;
                // if(!$rs){
                //     $eventSystem->add_error('苹果端回调-result',$request_uri,'更新数据错误失败');
                //     echo json_encode(array('ok'=>false,'message'=>'更新数据错误失败','products'=>array()));
                // }
                // $eventSystem->add_error('苹果端回调-result',$request_uri,'订单处理成功');
                echo json_encode(array('ok'=>true,'message'=>'ok','products'=>array('product'=>$productId,'state'=>$state)));
            } else {
               throw new \woo\base\AppException("User do not exist");
            }
		// 	$email = $obj->getEmail();
		// 	$type = $request->getProperty("type");
		// 	$language = (int)$request->getProperty("language");
		// 	if(!is_null($obj)){ // 如客户数据存在
		// 		$result['id'] = $obj->getId();
		// 		if($result["id"]){
		// 			if($action=="custom_resetPassword"){
		// 				// 创建唯一标识符
		// 				$hash = md5(uniqid(rand(1,1)));
		// 				$now = date('Y-m-d h:i:s');
		// 				// 把登录时间和登录次数写入 person 表
		// 				$person = new \woo\domain\Person(null);
		// 				$person->setId($result["id"]);
		// 				$person->setHash($hash);
		// 				$person->setLasttime($now);
		// 				$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','hash'));
		// 				$finder = new \woo\mapper\DomainObjectAssembler($factory);
		// 				$finder->insert($person);
			
		// 				$mail = new \woo\view\activeAccountEmail($email,$username,$hash,$type);
		// 				$mail->setLanguage($language);
		// 				$mail->send();
		// 				echo json_encode($result);
		// 			}
		// 		}
		// 	}else{
		// 		    throw new \woo\base\AppException($request->getFeedBackString());
		// 	}
        } catch(\woo\base\AppException $e) {
			$result["ok"] = false;
            $result["message"] = $e->getMessage();
            // $result['products'] = array();
			echo json_encode($result);
        }
    }
 
    /**
     * 验证这个交易号是否存在过了
     * @param $notify
     * @param $transId
     * @param $totalAmount
     * @param $tradeId
     * @param $receipt_data
     */
    public function check_apple_trans($table,$notify,$receipt_data) {
        $transId = $notify['transaction_id']; // 交易的标识
        $originalTransId = $notify['original_transaction_id'];  // 原始交易ID
        $productid = $notify['product_id'];
        // $transTime = $this->toTimeZone($notify['purchase_date']);  // 购买时间
        $cond = array(
            array("field"=>"origintransid","value"=>$originalTransId,"operator"=>"eq")
            // array("field"=>"productid","value"=>$productid,"operator"=>"eq")
        );
        $m = new \woo\mapper\FinalAssembler($table,array("id","origintransid","productid","content"),$cond);
        $objs = $m->find();
        $appleTransCnt = count($objs);
        if($appleTransCnt==0){ // 该笔内购未发生过
            // echo json_encode($validate);
            return array('status'=>1);
        } else {
            $content = $objs[0]['content'];
            $content_arr = json_decode($content,true);
            $data = $content_arr['notify'];
            $receipts = $data['latest_receipt_info']; // 自动续订的订阅项 时才会有
            if(!isset($data['latest_receipt_info'])){
                $receipts = $data['receipt']['in_app']; // 消费类型
            }
            foreach($receipts as receipt){
                if(receipt["transaction_id"] == $transId) { // 该笔内购发生过
                    return array('status'=>-1,'appleTransCnt'=>$appleTransCnt,'id'=>$objs[0]['id']);
                }
            }
            return array('status'=>0);
        }
    }

    private function toTimeZone($src, $from_tz = 'Etc/GMT', $to_tz = 'Asia/Shanghai', $fm = 'Y-m-d H:i:s') {
        $datetime = new \DateTime($src, new \DateTimeZone($from_tz));
        $datetime->setTimezone(new \DateTimeZone($to_tz));
        return $datetime->format($fm);
    }

    /**
     * 根据语言获取当前地区时间
     * 以本地服务器时间为准
     * 比美国纽约快12小时
     * 比泰国，印尼快1小时
     * 比葡萄牙里本斯快7小时
     * @param $language
     */
    private function format_time_zone($language,$is_format=true){
        if($language == 1){
            $f_time = strtotime('-12 hours');
        }else if($language == 2 || $language == 3){
            $f_time = strtotime('-1 hours');
        }else{//葡萄牙语
            $f_time = strtotime('-7 hours');
        }
        if($is_format){
            $f_time = date('Y-m-d H:i:s',$f_time);
        }
        return $f_time;
    }

    private function format_to_time_zone($time_zone){
        date_default_timezone_set($time_zone);//设置时区
        $f_time = date('Y-m-d H:i:s');
        date_default_timezone_set('Asia/Shanghai');//设置回默认的
        return $f_time;
    }

    /**
     * 21000 App Store不能读取你提供的JSON对象
     * 21002 receipt-data域的数据有问题
     * 21003 receipt无法通过验证
     * 21004 提供的shared secret不匹配你账号中的shared secret
     * 21005 receipt服务器当前不可用
     * 21006 receipt合法，但是订阅已过期。服务器接收到这个状态码时，receipt数据仍然会解码并一起发送
     * 21007 receipt是Sandbox receipt，但却发送至生产系统的验证服务
     * 21008 receipt是生产receipt，但却发送至Sandbox环境的验证服务
     */
    private function acurl($receipt_data, $sandbox=0, $password){
            
        //小票信息
        if($password != "") {
            $POSTFIELDS = array("receipt-data" => $receipt_data,'password'=>$password);
        } else {
            $POSTFIELDS = array("receipt-data" => $receipt_data); 
        }
        $POSTFIELDS = json_encode($POSTFIELDS); 
        //正式购买地址 沙盒购买地址 
        $url_buy = "https://buy.itunes.apple.com/verifyReceipt"; 
        $url_sandbox = "https://sandbox.itunes.apple.com/verifyReceipt"; 
        $url = $sandbox ? $url_sandbox : $url_buy; 
    
        //简单的curl
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $POSTFIELDS);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);  //这两行一定要加，不加会报SSL 错误
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * 验证AppStore内付
     * @param  string $receipt_data 付款后凭证
     * @return array                验证是否成功
     */
    protected function validate_apple_pay($receipt_data,$password=""){
        // 验证参数 
        if (strlen($receipt_data) < 20) { 
            $result = array( 'status' => false, 'message' => '非法参数'); 
            return $result; 
        } 
        // if (isset($_GET['debug'])) { 
        //     exit(json_encode($data)); 
        // } 

        // 请求验证
        $html = $this->acurl($receipt_data,0,$password);
        $data = json_decode($html,true);

        $data['sandbox'] = '0';
        // 如果是沙盒数据 则验证沙盒模式
        if($data['status']=='21007'){
            $html = $this->acurl($receipt_data, 1, $password);
            $data = json_decode($html,true);
            $data['sandbox'] = '1';
        }

        // $eventSystem = new \Freeios\Event\SystemEvent();
        // $eventSystem->add_error('苹果验证','validate_apple_pay',json_encode($data));

        // 判断是否购买成功
        if(intval($data['status'])===0){ // 成功
            $receipts = $data['latest_receipt_info']; // 自动续订的订阅项 时才会有
            if(!isset($data['latest_receipt_info'])){
                $receipts = $data['receipt']['in_app']; // 消费类型
            }
            if(count($receipts)>0){
                $maxDate = '0';  //最新的日期，时间戳
                $appData = null; //最新的那组数组
                foreach($receipts as $k=>$app){
                    if($maxDate<$app['purchase_date_ms']){
                        $appData = $app;
                        $maxDate = $app['purchase_date_ms'];
                    }
                }
                $result=array(
                    'status'=>true,
                    'message'=>'Purchase success',
                    'data'=>$appData,
                    'sdata'=>$data,
                    'sandbox'=>$data['sandbox'],
                );
            }else{
                $result=array(
                    'status'=>false,
                    'message'=>'No data status:'.$data['status']
                );
            }
        }else{ // 失败
            $result=array(
                'status'=>false,
                'message'=>'Failed purchase status:'.$data['status']
            );
        }
        return $result;
    }

    protected function checkIsSubscribed($notify, $productId) {
        $expires_date = '0';
        foreach($notify['latest_receipt_info'] as $appReceipt) {
            if($appReceipt['product_id'] != $productId) {
                continue;
            }
            if($expires_date<$appReceipt['purchase_date_ms']){
                $expires_date = $appReceipt['purchase_date_ms'];
            }
        }
        if($expires_date == '0') {
            return false;
        }
        if($expires_date > $notify['receipt']['request_date_ms'] && $expires_date > time()) {
            return true;
        }
        return false;
    }
}

// $controller = new BuyVaidiOSController();
// $controller->process();
?>
