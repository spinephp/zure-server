<?php
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
?>