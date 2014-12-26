<?php
namespace woo\controller;
require_once("base/RequestRegistry.php");

class Request{
    private $properties;
    private $feedback = array();
	private $objects = array();
	private $lastcmd;

    function __construct(){
        $this->init();
    }

    function init(){
        if(isset($_SERVER['REQUEST_METHOD'])){
            $data = json_decode(file_get_contents('php://input'),true); // 支持 AJAX 的 PUT DELETE 请求
            $data = is_null($data)? $_REQUEST:array_merge($_REQUEST, array("item"=>$data));
            if($_SERVER['REQUEST_METHOD']=="PUT" || $_SERVER['REQUEST_METHOD']=="DELETE"){
              foreach($data as $key=>$value){
                if(!is_array($value)){
                  if(preg_match("/\/(\d+)/",$value,$match)){
                    $data["id"] = $match[1];
                    $data[$key] = preg_replace("/\/\d+/","",$value);
                  }
                }
              }
              
              /*$token = $data["token"];
              preg_match("/\/(\d+)/",$token,$match);
              $data["id"] = $match[1];
              $data["token"] = preg_replace("/\/\d+/","",$token);*/
            }
            $this->properties = $data;
            $this->log($_SERVER['REQUEST_METHOD'].json_encode($data)); // 把本次请求与入日志文件
            return;
        }
        foreach($_SERVER['argv'] as $arg){
            if(strpos($arg,'=')){
                list($key,$val) = explode("=",$arg);
                $this->setProperty($key,$val);
            }
        }
}

    function getProperty($key){
        if(isset($this->properties[$key])){
          $val = $this->properties[$key];
          return $val;
        }
		return null;
    }

    function setProperty($key,$val){
        $this->properties[$key] = $val;
    }

    function getObject($key){
		if(isset($this->objects[$key])){
			return $this->objects[$key];
		}
	}

    function setObject($key,$val){
		$this->objects[$key] = $val;
	}

    function addFeedback($msg){
        array_push($this->feedback,$msg);
    }

    function getFeedback(){
        return $this->feedback;
    }

    function setCommand($cmd){
        $this->lastcmd = $cmd;
	}

	function getLastCommand(){
        return $this->lastcmd;
	}

    function getFeedbackString($separator="\n"){
        return implode($separator,$this->feedback);
    }
  function log( $logthis ){
    Date_default_timezone_set("PRC");
    file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . $logthis.PHP_EOL, FILE_APPEND | LOCK_EX);
  }
}

?>