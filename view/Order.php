<?php
/**
* 对数据表 order 进行 REST 操作
* 用于 AJAX 调用
* @package yrrweb
* @author  Liu xingming
* @copyright 2013/6/23 Azuresky safe group
*/
namespace woo\view;

require_once("view/REST.php");
require_once("domain/Ordersstate.php");

class postOrderREST extends postREST{
	
	// 生成订单号 code
	private function makeOrderCode(){
		$code = date("yW");
		$factory = \woo\mapper\PersistenceFactory::getFactory("order",array("id","code"));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$idobj = $factory->getIdentityObject()->field('code')->ge($code.'00')->field('code')->le($code.'99');
		$collection = $finder->find($idobj);
		$code .= '00';
		if($collection->Count()){
			$obj = $collection->next();
			$maxcode = (int)$obj->getCode();
			while($obj){
				$obj = $collection->next();
				if($obj){
					$tem = (int)$obj->getCode();
					if($tem>$maxcode) $maxcode = $tem;
				}
			}
			$code = $maxcode+1;
		} 
		return $code;
	}
	
	public function doAny($item){
		$code = $this->makeOrderCode();
	  
		/**
		* 处理 order 表数据
		*/
		$itemOrder = $item;
        unset($itemOrder['products']);

        if(array_key_exists('code',$itemOrder))
            $itemOrder['code'] = $code;
        else
            $extend['code'] = $code;

		$extend['userid'] = "?userid";
		$extend['time'] = "?time";
		$target["order"][] = array('fields'=>$itemOrder,'condition'=>$extend);

		/**
		* 处理 orderproduct 表数据，支持多条记录
		*/
		$factory = \woo\mapper\PersistenceFactory::getFactory("product",array("id","price","amount"));
		$finder = new \woo\mapper\DomainObjectAssembler($factory);
		$extendProduct['orderid'] = array('0'=>'id'); // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		foreach($item['products'] as $product){
			// 
			$idobj = $factory->getIdentityObject()->field('id')->eq($product["proid"]);
			$collection = $finder->find($idobj);
			$obj = $collection->current();
			$extendProduct["price"] = $obj->getPrice();
			$target["orderproduct"][] = array('fields'=>$product,'condition'=>$extendProduct);
		}

		/**
		* 处理 ordersstate 表数据
		*/
		$extendState['orderid'] = array('0'=>'id'); // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		$extendState['stateid'] = 1;
		$extendState['time'] = "?time";
		$target["ordersstate"][] = array('condition'=>$extendState);

		return $this->changeRecords($target,function($domain,&$result) use($factory,$finder){
			if(!empty($result['orderproduct'])){
				// 更新产品库存数量
				foreach($result['orderproduct'] as $goods){
					if($goods["number"]>0){
						$idobj = $factory->getIdentityObject()->field('id')->eq($goods["proid"]);
						$collection = $finder->find($idobj);
						$obj = $collection->current();
						$product = new \woo\domain\Product(null);
						$product->setId((int)$goods["proid"]);
						$num = $obj->getAmount()-$goods["number"]; // 新增生产数
						if($num<0) $num = 0;
						$product->setAmount(strval($num));
						$finder->insert($product);
					}
				}
				$products = $result['orderproduct'];
				$result['order'][0]['products'] = $products;
				unset($result['orderproduct']);
			}
			$order = $result['order'][0];
			unset($result['id']);
			unset($result['order']);
			$result = $order;
		},true);
	}
}

class putOrderREST extends putREST{
	
	public function doAny($item){
		/**
		* 处理 order 表数据
		*/
		$itemOrder = $item;
		unset($itemOrder['products']);
		$target["order"][] = array('fields'=>$itemOrder,'condition'=>$this->request->getProperty("id"));

		/**
		* 处理 orderproduct 表数据，支持多条记录
		*/
		foreach($item['products'] as $product)
			$target["orderproduct"][] = array('fields'=>$product);

		/**
		* 处理 ordersstate 表数据
		*/
		$extend['orderid'] = array('0'=>'id'); // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		$extend['stateid'] = array('0'=>'stateid');
		$extend['time'] = date("Y-m-d H:i:s",time());
		$target["ordersstate"][] = array('need'=>array('id','orderid','stateid','time'),'condition'=>$extend);

		return  $this->changeRecords($target,function($domain,&$result){
			if(!empty($result['orderproduct'])){
				$products = $result['orderproduct'];
				$result['order'][0]['products'] = $products;
				unset($result['orderproduct']);
			}
			$order = $result['order'][0];
			unset($result['id']);
			unset($result['order']);
			$result = $order;
		},false);
	}
}

new REST('order');

?>
