<?php
/**
 * 激活新注册用户账号
 * @package controller
 * @author  Liu xingming
 * @copyright 2014 Azuresky safe group
 * @return 如验证通过，返回包含用户信息的对象，否则返回指明验证失败信息的字符串
 */
namespace woo\controller;
require_once("controller/PageController.php");
require_once("base/SessionRegistry.php");

class ActiveAccountController extends PageController{

  function process(){
    try{
		  $session = \woo\base\SessionRegistry::instance();
      $request = $this->getRequest();
			$cmdStatus = $request->getFeedbackString();
      switch($cmdStatus){
        case "Command Ok!":
          $id = $request->getObject("id");
          $data = array('id'=>$id,'active'=>'Y','hash'=>"00000000000000000000000000000000");
        
          // 生成 person 目标
			    $person = new \woo\domain\Person(null);
          foreach($data as $field=>$value){
            $fun = "set".ucfirst($field);
            $person->$fun($value);
          }
      
          // 更新表 person 中的激活状态数据
			    $factory = \woo\mapper\PersistenceFactory::getFactory("person",array_keys($data));
		      $finder = new \woo\mapper\DomainObjectAssembler($factory);
			    $finder->insert($person);
          
				  // 如果激活账号是客户，则把邮箱标识为已验证邮箱
          $factory = \woo\mapper\PersistenceFactory::getFactory("custom",array('id','userid','emailstate'));
				  $finder = new \woo\mapper\DomainObjectAssembler($factory);
				  $idobj = $factory->getIdentityObject()->field('userid')->eq($id);
				  $collection = $finder->find($idobj);
          if($collection->count()==1){
				    $obj = $collection->current();
            // 生成 custom 目标
			      $custom = new \woo\domain\Custom(null);
            $custom->setId($obj->getId());
            $custom->setEmailstate('Y');
			      $finder->insert($custom);
          }
          $session->set("msgTip",'恭喜您，账号激活成功！');
          $this->forward('Main.php');
          //echo "<script> alert('恭喜您，账号激活成功！'); </script>"; 

          break;
        case "Link expired！":
          break;
			  default:
				  throw new \woo\base\AppException($cmdStatus);
      }
    }catch(Exception $e){
        echo $e->getMessage();
    }
  }
}

$controller = new ActiveAccountController();
$controller->process();
?>