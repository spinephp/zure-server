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

class ResetPasswordController extends PageController{
	function process(){
		try{
			$session = \woo\base\SessionRegistry::instance();
			$request = $this->getRequest();
			$obj = $request->getObject("person"); // 取要验证的客户数据
			$action = $request->getProperty('action');
			$username = $obj->getUsername();
			$email = $obj->getEmail();
			$type = $request->getProperty("type");
			$language = (int)$request->getProperty("language");
			if(!is_null($obj)){ // 如客户数据存在
				$result['id'] = $obj->getId();
				if($result["id"]){
					if($action=="custom_resetPassword"){
						// 创建唯一标识符
						$hash = md5(uniqid(rand(1,1)));
						// 把登录时间和登录次数写入 person 表
						$person = new \woo\domain\Person(null);
						$person->setId($result["id"]);
						$person->setHash($hash);
						$factory = \woo\mapper\PersistenceFactory::getFactory("person",array('id','hash'));
						$finder = new \woo\mapper\DomainObjectAssembler($factory);
						$finder->insert($person);
			
						$msg = <<<_EMAIL
						Dear user:
						Check on the following link to reset your password:
						http://www.yrr8.com/lostpassword.php?id=$hash
_EMAIL;
			
      $now = date('Y-m-d h:i:s');
      
      $url = "http://".$_SERVER["HTTP_HOST"];
      $url .= "/woo/index.php? cmd=PasswordVerify&verify={$hash}&type=$type";
      $subject = array("重设密码","Reset password"); //邮件标题
      $body_cn = "尊敬的 {$username} 您好!<br/>您于 $now 因忘记密码而申请系统重置，为保障您的帐户安全，请在24小时内点击该链接，您也可以将链接复制到浏览器地址栏访问：<br/> 
      <a href='{$url}' target= 
  '_blank'>{$url}</a><br/> 
      若您没有忘记密码 ，请发邮件至：yrrlyg@gmail.com<br />
      ------------------------------------------------------<br />
  您之所以收到这封邮件，是因为您曾经注册成为云瑞的用户。<br />
  本邮件由云瑞系统自动发出，请勿直接回复！<br />
  在购物中遇到任何问题，请点击 帮助中心。<br />
  如果您有任何疑问或建议，请联系我们。<br />
连云港云瑞耐火材料有限公司<br /><img alt='helloweba' src='cid:my-attach'>";
      $body_en = "Dear Valued Customer,<br/>You in the $now to apply for the validation email，In order to ensure the security of your account，Please click on the link within 24 hours，You can also copy the link to your browser's address bar：<br/> 
      <a href='{$url}' target= 
  '_blank'>{$url}</a><br/> 
      If you haven't applied for a validation email, please send email to：yrrlyg@gmail.com<br />
      ------------------------------------------------------<br />
  You are receiving this email because you have registered as YunRui users.<br />
  This E-mail made by YunRui system automatically, please do not reply directly!<br />
  Encounter any problems in shopping, please click on the help center.<br />
  If you have any questions or Suggestions, please contact us.<br />
LIANYUNGANG YUNRUI REFRACTORY CO,.LTD<br /><img alt='helloweba' src='cid:my-attach'>";
      $body = array($body_cn,$body_en); //邮件主体内容
      $this->sendEmail($email,$subject[$language],$body[$language]);
						echo json_encode($result);
					}
				}
			}else{
				    throw new \woo\base\AppException($request->getFeedBackString());
			}
        }catch(\woo\base\AppException $e){
			$result["id"] = -1;
			$result["username"] = $e->getMessage();
			echo json_encode($result);
        }
    }
  function sendEmail($email,$subject,$body){
    require_once('phpmailer/class.phpmailer.php');

		$mail = new \PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = "smtp.qq.com";//"smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Hostname = 'yrr8.com';
		$mail->Username = "1619584123";  //????? QQ ?
		$mail->Password = "lxm@tsl121314";  //????????
		$mail->SetFrom("admin@yrr8.com");
		$mail->Subject = $subject; //????
		$mail->AddAddress($email);

		$mail->CharSet  = "UTF-8"; //???
		$mail->Encoding = "base64"; //????


    //$mail->From = "yrrlyg@gmail.comm";  //发件人地址（也就是你的邮箱）
    //$mail->FromName = "云瑞";  //发件人姓名

    //$address = $email;//收件人email
    //$mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

    //$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
    //$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
    $mail->Body = $body;

    //发送
    if(!$mail->Send())
      throw new \woo\base\AppException("发送失败: " . $mail->ErrorInfo);
  }
}

$controller = new ResetPasswordController();
$controller->process();
?>