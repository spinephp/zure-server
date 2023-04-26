<?php
/**
* 数据表 Cart 的 REST 服务类
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/26 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");

class postCartREST extends postREST{
	public function doAny($item){
		$newperson = $this->request->getObject("person");
		if(is_null($newperson))
			throw new \woo\base\AppException($this->request->getFeedbackString());
		$cart = new \woo\domain\Cart(null);
		$cart->setUserId($newperson->getId());
		$cart->setType("P"); // 设定为个人用户
		$cart->setIp($_SERVER["REMOTE_ADDR"]);
		$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Cart");
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$finder->insert($cart);
		$item['id'] = $cart->getId();
		return $item;
	}
}

new REST('cart');
?>