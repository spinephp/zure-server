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
// require_once('domain/Level.php');

class AppleRenewController extends PageController{
	function process(){
		try{
            $state = 'CMD_OK';
            $session = \woo\base\SessionRegistry::instance();
            $request = $this->getRequest();
            $item = $request->getProperty('item');
            $table = "cloudlevelbuy";
            // notification_type 几种状态
            // NOTIFICATION_TYPE  描述
            // INITIAL_BUY  初次购买订阅。latest_receipt通过在App Store中验证，可以随时将您的服务器存储在服务器上以验证用户的订阅状态。
            // CANCEL  Apple客户支持取消了订阅。检查Cancellation Date以了解订阅取消的日期和时间。
            // RENEWAL  已过期订阅的自动续订成功。检查Subscription Expiration Date以确定下一个续订日期和时间。
            // INTERACTIVE_RENEWAL  客户通过使用应用程序界面或在App Store中的App Store中以交互方式续订订阅。服务立即可用。
            // DID_CHANGE_RENEWAL_PREF  客户更改了在下次续订时生效的计划。当前的有效计划不受影响。
            $notification_type = $item['notification_type'];//通知类型
            $password = $item['password']; // 共享秘钥
            if ($password == "1868c2da3302475db479c18c5452af51") {
                $receipt = isset($item['latest_receipt_info']) ? $item['latest_receipt_info'] : $item['latest_expired_receipt_info']; //latest_expired_receipt_info 好像只有更改续订状态才有
                $product_id = $receipt['product_id'];   // //商品的标识
                $original_transaction_id = $receipt['original_transaction_id'];  // //原始交易ID
                $transaction_id = $receipt['transaction_id'];  //  //交易的标识
                $purchaseDate = str_replace(' America/Los_Angeles','',$receipt['purchase_date_pst']);
                //查询出该apple ID最后充值过的用户
                $cond = array(array("field"=>"transactionid","value"=>$transaction_id,"operator"=>"eq"));
                $m = new \woo\mapper\FinalAssembler($table,array("id",'userid',"transactionid",'content'),$cond);
                $objs = $m->find();
                if(count($objs)>0){ // 
                    $userid = $objs['userid']; // 去数据库查询是否充值过
                    if ($notification_type == 'CANCEL') { //取消订阅，做个记录
                        if ($userid > 0) {
                            $content = array(
                                'appleTransCnt'=>count($objs),
                                'notify'=>$receipt,
                                'receipt_data'=>"");
                            $name = "\\woo\\domain\\".ucfirst($table);
                            $buy = new $name(null);
                            $buy->setUserid($userid);
                            $buy->setProductid($product_id);
                            $buy->setTransactionid($transaction_id);
                            $buy->setOrigintransid($original_transaction_id);
                            $buy->setContent(json_encode($content));
                            $buy->SetTime($purchaseDate);
                            $buy->setState(2);
                            $factory = \woo\mapper\PersistenceFactory::getFactory($table,array('id','state'));
                            $finder = new \woo\mapper\DomainObjectAssembler($factory);
                            $finder->insert($buy);
                            // $eventSystem->add_error('renew','AppleAutoPay','用户订阅取消记录成功');
                        }
                    } else {
                        //自动续订，给用户加时间
                        //排除几种状态不用处理，1，表示订阅续订状态的更改 2，表示客户对其订阅计划进行了更改 3，在最初购买订阅时发生
                        //if ($notification_type != "DID_CHANGE_RENEWAL_PREF" && $notification_type != "DID_CHANGE_RENEWAL_STATUS" && $notification_type != "INITIAL_BUY") {
                        if ($notification_type == "INTERACTIVE_RENEWAL" || $notification_type == "RENEWAL") {
                            $transTime = $this->toTimeZone($receipt['purchase_date']);
                            //查询数据库，该订单是否已经处理过了
                            $cond = array(
                                array("field"=>"transactionid","value"=>$transaction_id,"operator"=>"eq"),
                                array("field"=>"Time","value"=>$transTime,"operator"=>"eq")
                            );
                            $m = new \woo\mapper\FinalAssembler($table,array("id",'userid',"transactionid",'time'),$cond);
                            $objs = $m->find();
                            if(count($objs) == 0){ // 
                                // $order_type = $this->products[$product_id];
                                // $order_money = $this->product_money[$order_type];

                                // $eventSystem->add_error('renew','AppleAutoPay','续订成功');
                                $name = "\\woo\\domain\\".ucfirst($table);
                                $buy = new $name(null);
                                $buy->setUserid($userid);
                                $buy->setTransactionid($transaction_id);
                                $buy->setOrigintransid($original_transaction_id);
                                $buy->setContent(json_encode($content));
                                $buy->SetTime($purchaseDate);
                                $buy->setState(1);
                                $factory = \woo\mapper\PersistenceFactory::getFactory($table,array('id','state'));
                                $finder = new \woo\mapper\DomainObjectAssembler($factory);
                                $finder->insert($buy);
                                } else {
                                // $eventSystem->add_error('renew','AppleAutoPay','此次支付订单已处理过');
                            }
                        } else {
                            // $eventSystem->add_error('renew','AppleAutoPay','该类型通知不予处理--notification_type:' . $notification_type);
                        }
                    }
                }
            } else {
                // $eventSystem->add_error('renew','AppleAutoPay','该通知传递的密码不正确--password:' . $password);
            }

        }catch(\woo\base\AppException $e){
			$result["status"] = false;
			$result["message"] = $e->getMessage();
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
    public function check_apple_trans($table,$notify,$receipt_data){
        $transId = $notify['transaction_id'];   // 交易的标识
        $originalTransId = $notify['original_transaction_id'];  // 原始交易ID
        $transTime = $this->toTimeZone($notify['purchase_date']);  // 购买时间
        $cond = array(array("field"=>"transactionid","value"=>$transId,"operator"=>"eq"));
        $m = new \woo\mapper\FinalAssembler($table,array("id","transactionid"),$cond);
        $objs = $m->find();
        if(count($objs)==0){ // 该笔内购未发生过
            $content = array(
                'appleTransCnt'=>count($objs),
                'notify'=>$notify,
                'receipt_data'=>$receipt_data);
            // 把登录时间和登录次数写入 person 表
            $name = "\\woo\\domain\\".ucfirst($table);
            $buy = new $name(null);
            $buy->se