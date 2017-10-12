<?php
namespace woo\controller;
require_once("base/SessionRegistry.php");
//require_once("base/RequestRegistry.php");

class Request{
    private $properties;
    private $feedback = array();
	private $objects = array();
	private $lastcmd;
    function __construct(){
	$this->methodParam = '_method';
	$this->method = 'GET';
	$this->init();
    }
    
    public function getMethod(){
	return $this->method;
	}
	
	/**
	 * 返回当前请求的方法，请留意方法名称是大小写敏感的，按规范应转换为大写字母
	 * @assert (array("_method"=>"PUT")) == "PUT"
	 */
	public function findMethod($data)
	{
	    // $this->methodParam 默认值为 '_method'
	    // 如果指定 $_POST['_method'] ，表示使用POST请求来模拟其他方法的请求。
	    // 此时 $_POST['_method'] 即为所模拟的请求类型。
	    if (isset($data[$this->methodParam])) {
		$this->method = $data[$this->methodParam];

	    // 或者使用 $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'] 的值作为方法名。
	    } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
		$this->method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];

	    // 或者使用 $_SERVER['REQUEST_METHOD'] 作为方法名，未指定时，默认为 GET 方法
	    } else {
		$this->method = $_SERVER['REQUEST_METHOD'] ? $_SERVER['REQUEST_METHOD'] : 'GET';
	    }
	    $this->method = strtoupper($this->method);
	    return $this->method;
	}

  function decodeRSA($rsaData){
    $result = $rsaData;
    if(isset($rsaData["td"])){
      $session = \woo\base\SessionRegistry::instance();
      $rsa = new \woo\base\Rsa();
      $private_rsa = $session->get("token");

      $tem = $rsa->privateDecrypt(base64_decode($rsaData["td"]),$private_rsa);
      unset($result["td"]);
      $result = array_merge($result,json_decode(base64_decode($tem),true));
    }
    return $result;
  }

  function init(){
    if(isset( $_SERVER['REQUEST_METHOD'])){
      $data1 = null;
      $data2 = null;
      $data = json_decode(file_get_contents('php://input'),true); // 支持 AJAX 的 PUT DELETE 请求
      if(!is_null($data)){
        $data = $this->decodeRSA($data);
        $data1["item"] = isset($data["item"])? $data["item"]:$data;
      }

      if(!is_null($_REQUEST)){
        $data = $this->decodeRSA($_REQUEST);

        foreach($data as $key=>$val){
  				if($val!="")
  					$data2[$key] = $val;
  				else{
  					$jskey = json_decode($key,true);
  					$data2["item"] = isset($jskey["item"])? $jskey["item"]:$jskey;
  				}
        }
      }
      
      if(is_null($data1)){
        $data = $data2;
      }else{
        if(is_null($data2))
          $data = $data1;
        else
          $data = array_merge($data1,$data2);
     }
          //$data = is_null($data)? $_REQUEST:array_merge($_REQUEST, array("item"=>$data));
	$method = $this->findMethod($data);
         if($method=="PUT" || $method=="DELETE"){
	unset($data[$this->methodParam]);
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
          $this->log($method.json_encode($data)); // 把本次请求与入日志文件
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