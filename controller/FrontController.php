<?php
namespace woo\controller;

require_once("base/ApplicationRegistry.php");
require_once("controller/Request.php");
class FrontController{
    function handleRequest(){
        $request = new Request();
        $app_c = new \woo\base\ApplicationRegistry::AppController();

		while($cmd = $app_c->getCommand($request)){
            $cmd->execute($request);
		}
		$this->invokeView($app_c->getView($request));
    }

	function invokeView($target){
		$lctarget = strtolower($target);
		$uctarget = ucfirst($lctarget);
		if(file_exists("view/$uctarget.php"))
			include("view/$uctarget.php");
		else if(file_exists("domain/$uctarget.php")){
			include("view/REST.php");
			new \woo\view\REST($lctarget);
		}
		exit;
	}
}
?>