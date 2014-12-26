<?php
namespace woo\controller;

require_once("base/RequestRegistry.php");
require_once("controller/Request.php");

abstract class PageController{
    private $request;
    function __construct(){
        $request = \woo\base\RequestRegistry::getRequest();
        if(is_null($request)){$request = new Request();}
        $this->request = $request;
    }

    abstract function process();

    function forward($resource){
		if(file_exists("view/$resource"))
			include("view/$resource");
		else{
			include("view/REST.php");
			new \woo\view\REST(strtolower(substr($resource,0,strpos($resource,".php"))));
		}
        exit(0);
    }

    function getRequest(){
        return $this->request;
    }
}
?>