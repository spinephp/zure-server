<?php
namespace woo\controller;

require_once("base/RequestRegistry.php");
require_once("base/ApplicationRegistry.php");
require_once("controller/ApplicationHelper.php");
require_once("controller/Request.php");
require_once("command/CommandResolver.php");
require_once("domain/ObjectWatcher.php");
class Controller{
    private $applicationHelper;
    private function __construct(){}

    static function run(){
        ini_set('session.use_trans_sid', 0);
        $instance = new Controller();
        $instance->init();
        $instance->handleRequest();
    }

    function init(){
        $applicationHelper = ApplicationHelper::instance();
        $applicationHelper->init();
    }

    function handleRequest(){
        $request = \woo\base\RequestRegistry::getRequest();
		$app_c = \woo\base\ApplicationRegistry::appController();
		while($cmd = $app_c->getCommand($request)){
		    $cmd->execute($request);
		}
		\woo\domain\ObjectWatcher::instance()->performOperations();
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