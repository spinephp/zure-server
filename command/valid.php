<?php

class valid {
    	
	/**
	 * 验证用户后执行函数
	 * @param {object} $request - Request 类对象
	 * @param {object} $fun - 函数
	 * @return command status word
	 */
	protected function userShell(\woo\controller\Request $request,$fun=null){
		$session = \woo\base\SessionRegistry::instance();
		$userid = $session->get("userid");
		$result = 'CMD_INSUFFICIENT_DATA';
		$item = $request->getProperty('item');
		$cmd = $request->getProperty('cmd');
		if(!isset($userid) && isset($item['person']['id']))
			$userid = $item['person']['id'];
		

		if(isset($userid)){
			$result = 'CMD_OK';
			if(!empty($fun))
				$result = $fun($request,$userid);
		}else if($cmd=='Person' && isset($item['hash'])){
			// 重置密码
			$hash = $item['hash'];
			$cond = array(array("field"=>"hash","value"=>$hash,"operator"=>"eq"));
			$m = new \woo\mapper\FinalAssembler("person",array("id","hash","lasttime"),$cond);
			$objs = $m->find();
			if(count($objs)){
				$obj = $objs[0];
				if(count($obj)){
					$zero1=strtotime(date('y-m-d h:i:s')); //当前时间
					$zero2=strtotime($obj["lasttime"]);  //过年时间
					//$guonian=($zero2-$zero1)/86400; //60s*60min*24h
					if($zero2-$zero1< 2*86400){
						$userid = $obj["id"];
						$result = 'CMD_OK';
						if(!empty($fun))
							$result = $fun($request,$userid);
						$item['hash'] = '00000000000000000000000000000000';
						$request->setProperty('item',$item);
					}else{
						$request->addFeedback("Operation has expired!");
					}
				}else
					$request->addFeedback("Invalid option!");
			}else		
				$request->addFeedback("Invalid option!");
		}else
			$request->addFeedback("Not logged!");
		return is_numeric($result)? $result:self::statuses($result);
	}

}

?>