<?php
namespace woo\view;

require_once('view/SendEmail.php');

class activeAccountEmail extends yrrEmail
{
	//private $mail;
	private $subject;
	private $body;
	private $language=0;
	final function __construct($email,$username,$hash){
		parent::__construct($email);
		$this->setSubject(array("Yunrui user account activation","云瑞用户帐号激活"));
		$url = "http://".$_SERVER["HTTP_HOST"];
		$url .= "/woo/index.php? cmd=ActiveAccount&verify=$hash";
		$now = date("Y-m-d");
		$this->setBody(array(
			"Dear {$username} hello:<br/>
			Thank you for registering yrr8.com.<br/><br/>
			Please click the following link to activate your account:<br/>
			<a href='{$url}' target='_blank'>{$url}</a><br/>
			If the above link is not available, please copy it into your browser's address bar to access, which is valid for 48 hours.<br/><br />
			After you log on to the site (http://www.yrr8.com), you can enjoy the services provided by this site.<br/>
			Contact phone: +86 518 82340137<br/>
			If you have any questions please send email to admin@yrr8.com, welcome to contact us at any time!<br/>
			Wish you a blooming business and flourishing source of wealth.<br/>
			Lianyungang Yun Rui refractory Co., Ltd.<br/>{$now}<img alt='helloweba' src='cid:my-attach'>",

			"尊敬的 {$username} 您好：<br/>感谢您注册云瑞(yrr8.com)。<br/><br/>请点击以下链接激活您的帐号：<br/> 
			<a href='{$url}' target='_blank'>{$url}</a><br/> 
			如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接48小时内有效。<br/><br />  您登陆本站( http://www.yrr8.com )后,即可享受本站提供的各项服务了。<br />
			联系电话：+86 518 82340137<br />
			如果您有任何问题请发信到 admin@yrr8.com，欢迎随时与我们联系！<br />
			祝您生意兴隆，财源广进<br /><br />
			连云港云瑞耐火材料有限公司<br />{$now}<img alt='helloweba' src='cid:my-attach'>"
		));
	}
}
?>