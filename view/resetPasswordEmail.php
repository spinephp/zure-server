<?php
namespace woo\view;
require_once('view/SendEmail.php');

class activeAccountEmail extends yrrEmail
{
	final function __construct($email,$username,$hash,$type){
		parent::__construct($email);
		$this->setSubject(array("重设密码","Reset password"));
		$url = "http://".$_SERVER["HTTP_HOST"];
		$url .= "/woo/index.php? cmd=PasswordVerify&verify={$hash}&type=$type";
		$now = date('Y-m-d h:i:s');
 		$this->setBody(array(
			"尊敬的 {$username} 您好!<br/>您于 $now 因忘记密码而申请系统重置，为保障您的帐户安全，请在24小时内点击该链接，您也可以将链接复制到浏览器地址栏访问：<br/> 
			<a href='{$url}' target= 
			'_blank'>{$url}</a><br/> 
			若您没有忘记密码 ，请发邮件至：admin@yrr8.com<br />
			------------------------------------------------------<br />
			您之所以收到这封邮件，是因为您曾经注册成为云瑞的用户。<br />
			本邮件由云瑞系统自动发出，请勿直接回复！<br />
			在购物中遇到任何问题，请点击 帮助中心。<br />
			如果您有任何疑问或建议，请联系我们。<br />
			连云港云瑞耐火材料有限公司<br /><img alt='helloweba' src='cid:my-attach'>",

			"Dear Valued Customer,<br/>You in the $now to apply for the validation email，In order to ensure the security of your account，Please click on the link within 24 hours，You can also copy the link to your browser's address bar：<br/> 
			<a href='{$url}' target= 
			'_blank'>{$url}</a><br/> 
			If you haven't applied for a validation email, please send email to：yrrlyg@gmail.com<br />
			------------------------------------------------------<br />
			You are receiving this email because you have registered as YunRui users.<br />
			This E-mail made by YunRui system automatically, please do not reply directly!<br />
			Encounter any problems in shopping, please click on the help center.<br />
			If you have any questions or Suggestions, please contact us.<br />
			LIANYUNGANG YUNRUI REFRACTORY CO,.LTD<br /><img alt='helloweba' src='cid:my-attach'>"
		));
	}
}
?>