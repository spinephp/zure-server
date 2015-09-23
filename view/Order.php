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

class OrderREST extends REST{
	function __construct(){
		parent::__construct("order");
	}
	
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
	
	/**
	 * 添加订单
	 *
	 */
	function doCreate($item){
		try{
		
		$code = $this->makeOrderCode();
      
		/**
		* 处理 order 表数据
		*/
		$itemOrder = $item;
		if(is_null($itemOrder))
			throw new \woo\base\AppException("Order data is null!");
		unset($itemOrder['products']);
		$extend['code'] = $code; // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		$extend['userid'] = "?userid";
		$extend['time'] = "?time";
		$target["order"][] = array('fields'=>$itemOrder,'condition'=>$extend);

		/**
		* 处理 orderproduct 表数据，支持多条记录
		*/
		$extendProduct['orderid'] = array('0'=>'id'); // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		foreach($item['products'] as $product)
			$target["orderproduct"][] = array('fields'=>$product,'condition'=>$extendProduct);

		/**
		* 处理 ordersstate 表数据
		*/
		$extendState['orderid'] = array('0'=>'id'); // '0' - 表示表名在 $target 数组中的索引值，这里指 order ，'id' - 指定表名对应表中的字段名
		$extendState['stateid'] = 1;
		$extendState['time'] = "?time";
		$target["ordersstate"][] = array('condition'=>$extendState);

		$result = $this->changeRecords($target,function($domain,&$result){
			if(!empty($result['orderproduct'])){
				// 更新产品库存数量
				$factory = \woo\mapper\PersistenceFactory::getFactory("product",array("id","amount"));
				$finder = new \woo\mapper\DomainObjectAssembler($factory);
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
		$this->response(json_encode($result),201);
/*		
      // 生成订单域名目标
      $item = $this->request->getProperty("item");
			if(is_null($item))
				throw new \woo\base\AppException($this->request->getFeedbackString());
			$order = new \woo\domain\Order($code);
      foreach($item as $key=>$val){
        if($key!="id" && $key!="products"){
          $fun = "set".ucfirst($key);
          $order->$fun($val);
        }
      }
		  $session = \woo\base\SessionRegistry::instance();
      $userid = $session->get("userid");
      $order->setCode($code);
			$order->setUserid($userid);
			$order->setTime(date("Y-m-d H:i:s",time()));
      
      // 把订单数据插入表 order 中
			$finder->insert($order);
      
      // 生成订单中的产品域名目标
			$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\Product");
		  $finder = new \woo\mapper\DomainObjectAssembler($factory);
      $pros = $item["products"];
      $products = array();
      for($i=0;$i<count($pros);$i++){
			  $orderproduct = new \woo\domain\OrderProduct($code);
        $orderproduct->setOrderid($order->getId());
        $orderproduct->setProid($pros[$i]["proid"]);
        $orderproduct->setNumber($pros[$i]["number"]);
		    $idobj = $factory->getIdentityObject()->field('id')->eq($pros[$i]["proid"]);
		    $collection = $finder->find($idobj);
        $obj = $collection->current();
        $orderproduct->setPrice($obj->getPrice());
        $orderproduct->setReturnnow($obj->getReturnnow());
        $orderproduct->setModlcharge(0);
        $orderproduct->setEval("N");
        $orderproduct->setFeel("N");
        $products[] = $orderproduct;
        
        // 更新产品库存数量
        if($obj->getAmount()>0){
          $product = new \woo\domain\Product($code);
          $product->setId((int)$obj->getId());
          $num = $obj->getAmount()-$pros[$i]["number"]; // 新增生产数
          if($num<0) $num = 0;
          $product->setAmount(strval($num));
			    $finder->insert($product);
        }
      }
      
      // 把订单中的产品数据插入 order_pro 表中
			$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\OrderProduct");
		  $finder = new \woo\mapper\DomainObjectAssembler($factory);
      for($i=0;$i<count($products);$i++){
			  $finder->insert($products[$i]);
      }
      
      // 把订单状态数据插入 order_status 表中
			$orderstate = new \woo\domain\OrdersState($code);
      $orderstate->setOrderid($order->getId());
      $orderstate->setTime($order->getTime());
      $orderstate->setStateid("1");
			$factory = \woo\mapper\PersistenceFactory::getFactory("woo\\domain\\OrdersState");
		  $finder = new \woo\mapper\DomainObjectAssembler($factory);
			$finder->insert($orderstate);
      
			echo json_encode(array("id"=>$order->getId()));
*/		}catch(\woo\base\AppException $e){
			echo $e->getMessage();
		}
	}
	
  
	/**
	* 一次更新 order,orderproduct 和 ordersstate 数据表记录
	* @param $item - 键值对数组，支持 table:{field0:value0[,..]} 和 {field0:value0[,..]} 两种数据格式
	* @param $result - 键值对数组，与 $item 相对应，产生 {'id':id,table:{field0:value0[,..]}} 或者，{field0:value0[,..]} 格式数据
	*/
	function doUpdate($item){
		/**
		* 处理 order 表数据
		*/
		$itemOrder = $item;
		if(is_null($itemOrder))
			throw new \woo\base\AppException("Order data is null!");
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

		$result = $this->changeRecords($target,function($domain,&$result){
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
		$this->response(json_encode($result),201);
 	}
	
	function doDelete(){
	}
}

new OrderREST();

?>