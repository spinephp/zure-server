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
   * @param $data - 字符串数组，包含请求参数信息
   * @return 返回请求的方法
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

  /**
   * 如果输入数组是 RSA 加密数组则解密，否则直接返回输入数组
   * @param $rsaData - 字符串数组，包含请求参数信息,有可能被 RSA 加密
   * @return 返回明文数组
   */
  function decodeRSA($rsaData){
    $result = $rsaData;
    if(isset($rsaData["td"])){
      $session = \woo\base\SessionRegistry::instance();
      $rsa = new \woo\base\Rsa();
      $private_rsa = $session->get("rsa_private");

      $tem = $rsa->privateDecrypt(base64_decode($rsaData["td"]),$private_rsa);
      $tem1 = json_decode(base64_decode($tem),true);
      unset($result["td"]);
      if(is_array($result)){
        if(is_array($tem1))
          $result = array_merge($result,$tem1);
      } else{
        if(is_array($tem1))
          $result = $tem1;
        else
          $result = array();
      }
    }
    return $result;
  }

  /**
   * 获取请求参数，并写入白板. 支持 GET,POST,PUT,DELETE和命令行参数
   */
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
            
            $cond = $data["cond"];
            if(!is_null($cond) && !is_array(($cond))){
              $data["cond"] = json_decode($cond,true);
            }
            $filter = $data["filter"];
            if(!is_null($filter) && !is_array(($filter))){
              $data["filter"] = json_decode($filter,true);
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

  /**
   * 把本次请求参数与入日志文件
   */
  function log( $logthis ){
    Date_default_timezone_set("PRC");
    file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . $logthis.PHP_EOL, FILE_APPEND | LOCK_EX);
  }
}

?>