<?php
namespace woo\view;

require_once('phpmailer/class.phpmailer.php');

class yrrEmail
{
	protected $mail;
	private $subject;
	private $body;
	private $language=0;
	function __construct($email){
		$this->mail = new \PHPMailer(); // create a new object
		$this->mail->IsSMTP(); // enable SMTP
		$this->mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$this->mail->SMTPAuth = true; // authentication enabled
		$this->mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$this->mail->Host = "smtp.qq.com";//"smtp.gmail.com";
		$this->mail->Port = 465; // or 587
		$this->mail->IsHTML(true);
		$this->mail->Hostname = 'yrr8.com';
		$this->mail->Username = "1619584123";  //????? QQ ?
		$this->mail->Password = "lxm@tsl121314";  //????????
		$this->mail->SetFrom("admin@yrr8.com");
		$this->mail->AddAddress($email);

		$this->mail->CharSet  = "UTF-8"; //???
		$this->mail->Encoding = "base64"; //????
	}

	function send(){

		//$mail->From = "yrrlyg@gmail.comm";  //发件人地址（也就是你的邮箱）
		//$mail->FromName = "云瑞";  //发件人姓名

		//$address = $email;//收件人email
		//$mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

		//$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
		//$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
		$this->mail->Body = $this->body[$this->language];
		$this->mail->Subject = $this->subject[$this->language];

		//发送
		if(!$this->mail->Send())
			throw new \woo\base\AppException("邮件发送失败: " . $this->mail->ErrorInfo);
	}

	function setSubject(array $subject){
		$this->subject = $subject;
	}

	function setBody(array $body){
		$this->body = $body;
	}

	function setLanguage($language){
		$this->language = $language;
	}

	function getMail(){
		return $this->mail;
	}
}
?>