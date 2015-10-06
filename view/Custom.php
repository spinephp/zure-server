<?php
/**
* 数据表 Custom 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("domain/Person.php");
require_once("domain/Systemnotice.php");

class customREST extends REST{
	static $language = 0;
	function __construct(){
		parent::__construct("custom");
	}
	
	function sucessPerson($person,$finder,&$result){
		$pic = $person->getPicture();
		$s_dir = "images/user/".session_id();
		$s_file = $s_dir."/".$pic;
		if(!empty($pic) && $pic!="noimg.png" && file_exists($s_file)){
			$imgName = sprintf("u%08d",$person->getId());
			$headshot = $imgName.".png";
			rename($s_file, "images/user/$headshot");
			$person->setPicture($headshot);
			$finder->insert($person);
			$result['picture'] = $headshot;
			rmdir($s_dir);
		}
	}
	
	function doCreate($item){
		$lang = $item["language"];
		if(isset($lang))
			self::$language = $lang;
		$itemPerson = $item["person"];
		if(is_null($itemPerson))
			throw new \woo\base\AppException("Person data is null!");

		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$extend['registertime'] = $now;
		$extend['lasttime'] = $now;
		$extend['hash'] = md5($itemPerson['username'].$itemPerson['pwd'].$now);
		$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extend,'sucess'=>"sucessPerson");

		$itemCustom = $item["custom"];
		if(is_null($itemCustom))
			throw new \woo\base\AppException("Custom data is null!");
		$extCustom['userid'] = array('0'=>'id');
		if(empty($itemCustom['type']))
			$extCustom['type'] = 'P';
		if(empty($itemCustom['ip']))
			$extCustom['ip'] = $_SERVER["REMOTE_ADDR"];
		$target["custom"][] = array('fields'=>$itemCustom,'condition'=>$extCustom);

		$extCustomgrade['userid'] = array('0'=>'id');
		$extCustomgrade['gradeid'] = 1;
		$extCustomgrade['date'] = $now;
		$target["customgrade"][] = array('condition'=>$extCustomgrade);

		$extNotice['userid'] = array('0'=>'id');
		$extNotice['type'] = 'G';
		$extNotice['content'] = '恭喜你注册成功，你目前是云瑞注册会员，&lt;a href=#&gt;查看会员的权利及优惠&lt;/a&gt;';
		$extNotice['time'] = $now;
		$target["systemnotice"][] = array('condition'=>$extNotice);

		//$this->createRecords($target,function($domain,&$result){
		$result = $this->changeRecords($target,function($domain,&$result){
			$register = array("Account registration success!","账号注册成功！");
			$email = array( 
			"Account activation email has been sent to your mailbox. Activate your message within 48 hours. 
			Please log in as soon as possible to activate the link to complete the account activation.",
			"账号激活邮件已发送到你的邮箱中。激活邮件48小时内有效。请尽快登录您的邮箱点击激活链接完成账号激活。"
			);
			// 发送激活邮件
			customREST::activeEmail($domain[0]->getUsername(),$domain[0]->getEmail(),$domain[0]->getHash());
			$result['id'] = $result['custom']['id'];
			$result['custom']['userid'] = $domain[0]->getId();
			$result["register"] = $register[customREST::$language];
			$result["email"] = $email[customREST::$language];
			unset($result['person']['pwd']);
		},true);
		$this->response(json_encode($result),201);
	}
	
	function doUpdate($item){
		$target = array();
		if(empty($item["custom"]))
			throw new \woo\base\AppException("Custom data is null!");
		$target["custom"][] = array('fields'=>$item["custom"],'condition'=>$this->request->getProperty("id"));

		if(!empty($item["person"])){
			$extPerson['id'] = array('0'=>'userid');
			$target["person"][] = array('fields'=>$item["person"],'condition'=>$extPerson);
		}
		if(count($target)==0)
			throw new \woo\base\AppException("Data is null!");
		$result = $this->changeRecords($target,function($domain,&$result){
		},false);
		$this->response(json_encode($result),201);
	}
	
	function doDelete(){

		// 从 billfree 表中删除记录
		// 从 billsale 表中删除记录
		// 从 cart 表中删除记录
		// 从 company 表中删除记录
		// 从 consignee 表中删除记录
		// 从 customgrade 表中删除记录
		// 从 customaccount 表中删除记录
		// 从 order_complain 表中删除记录
		// 从 order_eval 表中删除记录
		// 从 order_pro 表中删除记录
		// 从 order_status 表中删除记录
		// 从 proeval 表中删除记录
		// 从 pro_care 表中删除记录
		// 从 pro_consult 表中删除记录
		// 从 pro_use 表中删除记录
		// 从 sys_notice 表中删除记录
		$target = array(
			"custom"=>array('fields'=>array('id','userid'),'value'=>$this->request->getProperty("id")),
			"person"=>array('fields'=>array('id','companyid','picture'),'value'=>array('0'=>'userid')),
			"billfree"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"billsale"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"cart"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"company"=>array('fields'=>array('id'),'value'=>array('1'=>'companyid')),
			"consignee"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"customgrade"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"customaccount"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id')),
			"order"=>array('fields'=>array('userid','id'),'value'=>array('1'=>'id'))
		);
		$this->deleteRecords($target,function($domain,&$result){
			$pic = $domain[1]->getPicture();
			if(!empty($pic) && $pic!='noimg.png' && file_exists("images/user/$pic"))
				unlink("images/user/$pic");
			$result['id'] = $result['custom']['id'];
		});
	}
  
	static function activeEmail($username,$email,$token){
		require_once('phpmailer/class.phpmailer.php');
		$subject = array("Yunrui user account activation","云瑞用户帐号激活");
		$url = "http://".$_SERVER["HTTP_HOST"];
		$url .= "/woo/index.php? cmd=ActiveAccount&verify=$token";
		$now = date("Y-m-d");
		$body = array(
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
		);
		$mail = new \PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
		$mail->Host = "smtp.qq.com";//"smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Hostname = 'yrr8.com';
		$mail->Username = "1619584123";  //你的邮箱或 QQ 号
		$mail->Password = "lxm@tsl121314";  //你的贸易独立密码
		$mail->SetFrom("admin@yrr8.com");
		$mail->Subject = $subject[self::$language]; //邮件标题
		$mail->AddAddress($email);

		$mail->CharSet  = "UTF-8"; //字符集
		$mail->Encoding = "base64"; //编码方式


		//$mail->From = "yrrlyg@gmail.comm";  //发件人地址（也就是你的邮箱）
		//$mail->FromName = "云瑞";  //发件人姓名

		//$address = $email;//收件人email
		//$mail->AddAddress($address, "亲");//添加收件人（地址，昵称）

		//$mail->AddAttachment('xx.xls','我的附件.xls'); // 添加附件,并指定名称
		//$mail->AddEmbeddedImage("logo.jpg", "my-attach", "logo.jpg"); //设置邮件中的图片
		$mail->Body = $body[self::$language]; //邮件主体内容

		//发送
		if(!$mail->Send())
			throw new \woo\base\AppException("帐号激活邮件发送失败: " . $mail->ErrorInfo);
	}
}

new customREST();
?>