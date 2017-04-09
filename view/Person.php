<?php
/**
* 数据表 Person 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("domain/Person.php");
require_once("domain/Systemnotice.php");

class postPersonREST extends postREST{
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
		$person = $item["person"];
		if(is_null($person))
			throw new \woo\base\AppException("Person data is null!");

		date_default_timezone_set("PRC");
		$now = date('Y-m-d H:i:s');
		$extend['registertime'] = $now;
		$extend['lasttime'] = $now;
		$extend['times'] = 0;
		$extend['hash'] = md5($person['username'].$person['pwd'].$now);
		$target["person"][] = array('fields'=>$person,'condition'=>$extend,'sucess'=>"sucessPerson");

		//$this->createRecords($target,function($domain,&$result){
		return $this->changeRecords($target,function($domain,&$result){
			// 发送激活邮件
			postPersonREST::activeEmail($domain[0]->getUsername(),$domain[0]->getEmail(),$domain[0]->getHash(),$language);
			
			$_target = "person";
			$s = $result[$_target][0];
			unset($result[$_target]);
			$result[$_target] [0]= $s;
			
			$result['id'] = $domain[0]->getId();
			$result["register"] = postPersonREST::$register[$language];
			$result["email"] = postPersonREST::$email[$language];
			unset($result['person'][0]['pwd']);
		},true);
	}
  
	static function activeEmail($username,$email,$token,$language){
		require_once('view/activeAccountEmail.php');
		$mail = new activeAccountEmail($email,$username,$token);
		$mail->setLanguage($language);
		$mail->send();
	}
}

class putPersonREST extends putREST{
	private $language = 0;
	
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

		$person = isset($item["person"])? $item["person"]:$item;
		if(!empty($person)){
			// Re-send active email
			if(isset($person["active"]) && $person["active"]=="Y"){
				$now = date('Y-m-d H:i:s');
				$extend['lasttime'] = $now;
				$extend['hash'] = md5($person['username'].$person['email'].$now);
				unset($person["active"]);
				$activeAccount = true;
			}

			$target["person"][] = array('fields'=>$person,'condition'=>$extend,'sucess'=>"sucessPerson");
		}
		if(count($target)==0)
			throw new \woo\base\AppException("Data is null!");
		return $this->changeRecords($target,function($domain,&$result) use($activeAccount,$item){
			if($activeAccount){
				// 发送激活邮件
				postPersonREST::activeEmail($domain[0]->getUsername(),$domain[0]->getEmail(),$domain[0]->getHash(),$item['language']);
				$result['message'] = postPersonREST::$email[$item['language']];
			}
		},false);
	}
}

class deletePersonREST extends deleteREST{
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
				"person"=>array('fields'=>array('id','companyid','picture'),'value'=>$request->getProperty("id")),
				"billfree"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"billsale"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"cart"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"company"=>array('fields'=>array('id'),'value'=>array('0'=>'companyid')),
				"consignee"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"customgrade"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"customaccount"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id')),
				"order"=>array('fields'=>array('userid','id'),'value'=>array('0'=>'id'))
			);
			return $this->deleteRecords($target,function($domain,&$result){
				$pic = $domain[1]->getPicture();
				if(!empty($pic) && $pic!='noimg.png' && file_exists("images/user/$pic"))
					unlink("images/user/$pic");
				$result['id'] = $result['person']['id'];
			});
		}catch(\woo\base\AppException $e){
			throw new  \woo\base\AppException($e->message);
		}
	}
	
}
new REST('Person');

?>