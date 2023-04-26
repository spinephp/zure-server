<?php
/**
* 定义客户登录命令
* 流程如下：
* 1. 检查验证码是否正确，如正确执行下一步，否则转 5
* 2. 在数据库中查找与用户名和密码对应的记录，如记录存在执行下一步，否则转 4
* 3. 把记录和反馈信息保存到白板，返回 CMD_OK
* 4. 把用户名和密码错误信息保存到白板，返回 CMD_INSUFFICIENT_DATA
* 5. 把验证码错误信息保存到白板，返回 CMD_INSUFFICIENT_DATA
*
* @package command
* @author  Liu xingming
* @copyright 2012 Azuresky safe group
*/

namespace woo\command;

require_once("command/command.php");
require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("domain/Level.php");
class PurchasediOS extends Command{
	function doExecute(\woo\controller\Request $request){
    		$result = 1;//$this->captchaShell($request);
    		if($result==1)
    			$result = $this->safeShell($request,'payendtovalidate');
    		return $result;
	}
    /** 
     * 客户端支付完后调用的接口 
     * 验证通过后 做后续业务逻辑处理 
     */ 
    public function payendtovalidate() { 
		$state = 'CMD_OK';
		$session = \woo\base\SessionRegistry::instance();
        $receipt_data = $request->getProperty('receipt');
        $sendbox = $request->getProperty('sendbox');
        //待验证数据 
        $validate = $this->validate_apple_pay($receipt_data, $sendbox); 
        if (!$validate['status']) { //验证不通过 
            exit(json_encode(['state' => '10', 'msg' => $validate['message']])); 
        } 
        //进行验证通过的逻辑处理 
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
            $result = array( 'status' => false, 'message' => '购买失败 status:' . $data['status'] ); 
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
    function acurl($receipt_data, $sandbox = 0) { 
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

	function _resetPWD(\woo\controller\Request $request){
		$state = 'CMD_OK';
		$session = \woo\base\SessionRegistry::instance();
		$username = $request->getProperty("username");
		$email = $request->getProperty("email");
		$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','username','email'));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('username')->eq($username)->field('email')->eq($email);
		$collection = $finder->find($idobj);
		$obj = $collection->current();
		if(!$obj){ // 记录是否存在
			$request->addFeedback("Invalid username or email！");
			$state = 'CMD_INSUFFICIENT_DATA';
		}else{
			$request->setObject('person',$obj);
			$request->addFeedback(COMMAND_OK);
		}
		return self::statuses($state);
	}
}
?>