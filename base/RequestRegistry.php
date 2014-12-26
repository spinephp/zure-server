<?php
namespace woo\base;
require_once("base/Registry.php");
require_once("controller/Request.php");

class RequestRegistry extends Registry {
    private $request;
    private $values = array();
    private static $instance;
    
    private function __construct(){}
    static function instance(){
        if(!isset(self::$instance)){self::$instance=new self();}
        return self::$instance;
    }

    protected function get($key){
        if(isset($this->values[$key])){
            return $this->values[$key];
        }
        return null;
    }
 
    protected function set($key,$val){
        $this->values[$key] = $val;
    }

    static function getRequest(){
	    $that = self::instance();
		if(!isset($that->request)){
		    $that->request = new \woo\controller\Request();
		}
        return $that->request;
    }

    static function setRequest(\woo\controller\Request $request){
        return self::instance()->set('request',$request);
    }
}
?>