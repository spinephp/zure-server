<?php
namespace woo\command;

require_once("command/command.php");
require_once("controller/Request.php");
require_once("domain/Department.php");
require_once("base/SessionRegistry.php");
class Department extends RESTCommand{
	
    function restGet(\woo\controller\Request $request){
		  return $this->employeeShell($request,function($request){ 
        $request->addFeedback("Command Ok!");
			return 'CMD_OK';
      });
	}
	
	/**
	 * 在表 Department 中新增一条记录
	 * @param {object} $request - Request 类对象
	 * @return command status word
	 */
    function restCreate(\woo\controller\Request $request){
      return $this->captchaShell($request,function($request){
        return $this->employeeShell($request,function($request,$userid){
          $item = $request->getProperty("item");
          $status = 'CMD_OK';
          if($item['action']=="department_create"){
			      $request->addFeedback("Command Ok!");
          }else{
			      $request->addFeedback("Request error!");
			      $status = 'CMD_INSUFFICIENT_DATA';
          }
			    return $status;
        });
      });
	}
					
    function restUpdate(\woo\controller\Request $request){
      return $this->captchaShell($request,function($request){
        return $this->employeeShell($request,function($request,$userid){
          $item = $request->getProperty("item");
          $status = 'CMD_OK';
          if($item['action']=="department_update"){
			      $request->addFeedback("Command Ok!");
          }else{
			      $request->addFeedback("Request error!");
			      $status = 'CMD_INSUFFICIENT_DATA';
          }
			    return $status;
        });
      });
	}
	
  function restDelete(\woo\controller\Request $request){
    return $this->captchaShell($request,function($request){
      return $this->employeeShell($request,function($request,$userid){
        $id = $request->getProperty("id");
        $action = $request->getProperty("action");
        $status = 'CMD_OK';
				if(isset($id) && is_numeric($id)){
          if($action=="department_delete"){
			      $request->addFeedback("Command Ok!");
          }else{
			      $request->addFeedback("Request error!");
			      $status = 'CMD_INSUFFICIENT_DATA';
          }
				}else{
					$request->addFeedback("ID is needed!");
					$result = 'CMD_INSUFFICIENT_DATA';
				}
			  return $status;
      });
    });
	}
}
?>