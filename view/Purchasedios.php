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

class PurchasediOSController extends PageController{
	function process(){
		try{
            $state = 'CMD_OK';
            $session = \woo\base\SessionRegistry::instance();
            $request = $this->getRequest();
            $item = $request->getProperty('item');
            $receipt_data = $item['receipt'];
            $sendbox = $item['sendbox'];
            //待验证数据 
            $validate = $this->validate_apple_pay($receipt_data, $sendbox); 
            if (!$validate['status']) { //验证不通过 
                exit(json_encode($validate)); 
            } 
            //进行验证通过的逻辑处理 
            $table = "";
            $subClass = array("level","cloudlevel");
            $sep = DIRECTORY_SEPARATOR;
            foreach($item as $key=>$value) {
                if(in_array($key,$subClass)) {
                    $table = $key;
                    $filepath = "woo{$sep}domain{$sep}{\ucfirst($key)}.php";
                    @include_once("$filepath");
                    break;
                }
            }
            $id = $item[$table]["id"];
            $status = $item[$table]["purchasedState"];
			$cond = array(array("field"=>"id","value"=>$id,"operator"=>"eq"));
			$m = new \woo\mapper\FinalAssembler($table,array("id","state","lasttime"),$cond);
			$objs = $m->find();
			if(count($objs)){
				$obj = $objs[0];
				if(count($obj)){
                    $now = date('Y-m-d h:i:s');
                    // 把登录时间和登录次数写入 person 表
                    $name = "\\woo\\domain\\".ucfirst($table);
                    $person = new $name(null);
                    $person->setId($obj["id"]);
                    $person->setState($obj["state"] | $status);
                    $person->setLasttime($now);
                    $factory = \woo\mapper\PersistenceFactory::getFactory($table,array('id','state','lasttime'));
                    $finder = new \woo\mapper\DomainObjectAssembler($factory);
                    $finder->insert($person);
                    echo json_encode($validate);
                }
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
        }catch(\woo\base\AppException $e){
			$result["status"] = false;
			$result["message"] = $e->getMessage();
			echo json_encode($result);
        }
    }
    
    /** 
     * 验证AppStore内付 
     * @param string $receipt_data 付款后凭证 
     * @return array 验证是否成功 
     */ 
    function validate_apple_pay($receipt_data, $sendbox) { 
        // 验证参数 
        if (strlen($receipt_data) < 20) { 
            $result = array( 'status' => false, 'message' => '非法参数' ); 
            return $result; 
        } 
        // 请求验证 
        $html = $this->acurl($receipt_data, $sendbox); 
        $data = json_decode($html, true); 
        // 如果是沙盒数据 则验证沙盒模式 
        if ($data['status'] == '21007') { 
            // 请求验证 
            $html = $this->acurl($receipt_data,1); 
            $data = json_decode($html, true); 
            $data['sandbox'] = '1'; 
        } 
        if (isset($_GET['debug'])) { 
            exit(json_encode($data)); 
        } 
        // 判断是否购买成功 
        if (intval($data['status']) === 0) { 
            $result = array( 'status' => true, 'message' => '购买成功' ); 
        } else { 
            $result = array( 'status' => false, 'message' => '购买失败 error:' . $data['status'] ); 
        } 
        return $result; 
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
    function acurl($receipt_data, $sandbox = false) { 
        //小票信息 
        $POSTFIELDS = array("receipt-data" => $receipt_data); 
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
        $result = curl_exec($ch); 
        curl_close($ch); 
        return $result; 
    }

}

$controller = new PurchasediOSController();
$controller->process();
?>