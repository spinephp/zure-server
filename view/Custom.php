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

class postCustomREST extends postREST{
	private $language = 0;
	static $register = array("Account registration success!","账号注册成功！");
	static $email = array( 
		"Account activation email has been sent to your mailbox. Activate your message within 48 hours. 
		Please log in as soon as possible to activate the link to complete the account activation.",
		"账号激活邮件已发送到你的邮箱中。激活邮件48小时内有效。请尽快登录您的邮箱点击激活链接完成账号激活。"
	);
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
			$result[0]['picture'] = $headshot;
			self::deldir($s_dir);
		}
	}
	
	public function doAny($item){
		$lang = $item["language"];
		if(isset($lang))
			$language = $lang;
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
		return $this->changeRecords($target,function($domain,&$result){
			// 发送激活邮件
			activeEmail($domain[0]->getUsername(),$domain[0]->getEmail(),$domain[0]->getHash());
			
			foreach(array("custom","person") as $_target){
				$s = $result[$_target][0];
				unset($result[$_target]);
				$result[$_target] [0]= $s;
			}
			$result['id'] = $result['custom'][0]['id'];
			$result['custom'][0]['userid'] = $domain[0]->getId();
			$result["register"] = postCustomREST::$register[$language];
			$result["email"] = postCustomREST::$email[$language];
			unset($result['person'][0]['pwd']);
		},true);
	}
  
	function activeEmail($username,$email,$token){
		require_once('"view/activeAccountEmail.php"');
		$mail = new activeAccountMail($email,$username,$token);
		$mail->setLanguage($language);
		$mail->send();
	}
}

class putCustomREST extends putREST{
	
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
			$result[0]['picture'] = $headshot;
			self::deldir($s_dir);
		}
	}
	
	public function doAny($item){
		$target = array();
		if(empty($item["custom"]))
			throw new \woo\base\AppException("Custom data is null!");
		$target["custom"][] = array('fields'=>$item["custom"],'condition'=>$this->request->getProperty("id"));

		$itemPerson = $item["person"];
		if(!empty($itemPerson)){
			$extPerson['id'] = array('0'=>'userid');

			// Re-send active email
			if(isset($itemPerson["active"]) && $itemPerson["active"]=="Y"){
				$now = date('Y-m-d H:i:s');
				$extend['lasttime'] = $now;
				$extend['hash'] = md5("active".$now);
				unset($itemPerson["active"]);
				$activeAccount = true;
			}

			$target["person"][] = array('fields'=>$itemPerson,'condition'=>$extPerson,'sucess'=>"sucessPerson");
		}
		if(count($target)==0)
			throw new \woo\base\AppException("Data is null!");
		return $this->changeRecords($target,function($domain,&$result) use($activeAccount){
			if($activeAccount){
				// 发送激活邮件
				activeEmail($domain[1]->getUsername(),$domain[1]->getEmail(),$domain[1]->getHash());
			}
		},false);
	}
}

class deleteCustomREST extends deleteREST{
	public function doMethod(\woo\controller\Request $request){
		try{
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
				"custom"=>array('fields'=>array('id','userid'),'value'=>$request->getProperty("id")),
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
			return $this->deleteRecords($target,function($domain,&$result){
				$pic = $domain[1]->getPicture();
				if(!empty($pic) && $pic!='noimg.png' && file_exists("images/user/$pic"))
					unlink("images/user/$pic");
				$result['id'] = $result['custom']['id'];
			});
		}catch(\woo\base\AppException $e){
			throw new  \woo\base\AppException($e->message);
		}
	}
	
}
new REST('custom');

?>