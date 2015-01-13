<?php
/**
* 定义产品关注命令
* @package command
* @author  Liu xingming
* @copyright 2013 Azuresky safe group
*/

namespace woo\command;

require_once("command/command.php");
require_once("controller/Request.php");
require_once("base/SessionRegistry.php");
require_once("domain/Productcare.php");
//require_once("mapper/Collection.php");
//require_once("mapper/ProductCarePersistenceFactory.php");
require_once("mapper/DomainObjectAssembler.php");
class ProductCare extends RESTCommand{
  function restGet(\woo\controller\Request $request){
	return $this->userShell($request,function($request){
		$request->addFeedback("Command Ok!");
		return 'CMD_OK';
	});
  }
    
  function restCreate(\woo\controller\Request $request){
    return $this->userShell($request,function($request,$userid){
      $item = $request->getProperty("item");
      $factory = \woo\mapper\PersistenceFactory::getFactory("productcare",array('id','proid','userid'));
      $finder = new \woo\mapper\DomainObjectAssembler($factory);
      $idobj = $factory->getIdentityObject()->field('proid')->eq($item['proid'])->field("userid")->eq($userid);
      $collection = $finder->find($idobj);
      $obj = $collection->current();
      if($obj){
        $request->addFeedback("The products of you with focus on already have done.");
        return 'CMD_INSUFFICIENT_DATA';
      }else{
        if(!isset($item["userid"]))
          $item["userid"] = $userid;
        if(!isset($item["date"]))
          $item["date"] = date("Y-m-d m:i:s");
          
        $request->addFeedback("Command Ok!");
        return 'CMD_OK';
      }
    });
  }
  function restUpdate(\woo\controller\Request $request){}
  function restDelete(\woo\controller\Request $request){
		return $this->userShell($request,function($request){
				$id = $request->getProperty("id");
				$result = 'CMD_OK';
				if(isset($id) && !empty($id)){
					$request->addFeedback("Command Ok!");
				}else{
					$request->addFeedback("Invalid paramter!");
					$result = 'CMD_INSUFFICIENT_DATA';
				}
				return $result;
		});
  }
}
?>