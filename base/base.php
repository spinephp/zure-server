<?php
namespace woo\base;
require_once("controller/Request.php");

abstract class Registry{
    abstract protected function get($key);
    abstract protected function set($key,$val);
}

class RequestRegistry extends Registry{
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
        return self::instance()->get('request');
    }

    static function setRequest(\woo\controller\Request $request){
        return self::instance()->set('request',$request);
    }
}

class SessionRegistry extends Registry{
    private static $instance;
    private function __construct(){
        session_start();
    }

    static function instance(){
        if(!isset(self::$instance)){self::$instance = new self();}
        return self::$instance;
    }

    protected function get($key){
        if(isset($_SESSION[__CLASS__][$key])){
            return $_SESSION[__CLASS__][$key];
        }
        return null;
    }

    protected function set($key,$val){
        $_SESSION[__CLASS__][$key] = $val;
    }

    function setComplex(Complex $complex){
        self::instance()->set('complex',$complex);
    }

    function getComplex(){
        return self::instance()->get('complex');
    }
}
?>