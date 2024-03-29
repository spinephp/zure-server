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
    
      public function setMethod($method){
        $this->method = $method;
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
      $p = file_get_contents('php://input');
      if(!is_null($p)){
        $pos = strpos($p,"}}");
        if($pos>0)
        $p = substr($p,0,$pos+2);
      }
      $data = json_decode($p,true);
      // $data = json_decode(file_get_contents('php://input'),true); // 支持 AJAX 的 PUT DELETE 请求
      if(!is_null($data)){
        $data = $this->decodeRSA($data);
        if(!isset($data["item"])){
          $data1["item"] = $data;
        } else {
          $data1 = $data;
        }
      }

      if(!is_null($_REQUEST)){
        $data = $this->decodeRSA($_REQUEST);

        foreach($data as $key=>$val){
          if($val!="")
            if(strlen($key)<50) {
              $data2[$key] = $val;
            } else {
              // $tem = $key.$val;
              // $data2["item"] = json_decode($tem,true);
            }
  				else{
            $jskey = json_decode($key,true);
            if (!is_null($jskey))
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
          $data = array_merge($data2,$data1);
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
          foreach(array("cond","filter") as $name){
            if(isset($data[$name])){
              $cond = $data[$name];
              if(!is_null($cond) && !is_array(($cond))){
                $data[$name] = json_decode($cond,true);
              }
            }
          }

          $this->properties = $data;
          // $sessionid = $this->getProperty("token");
          // $sessionid1 = session_id();
          // if($sessionid!=session_id()){
          //   session_id($sessionid);
          // }
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
      $val = null;
        if(isset($this->properties[$key])){
          $val = $this->properties[$key];
        } else if(isset($this->properties["item"])){
          $item = $this->properties["item"];
          if(isset($item[$key])){
            $val = $item[$key];
          }
        }
		return $val;
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
    $content = NULL;
    $file = 'logfile.log';
    //file_put_contents('logfile.log', date("Y-m-d H:i:s"). " " . $logthis.PHP_EOL, FILE_APPEND | LOCK_EX);
    if (file_exists($file)) {
      $content = @file_get_contents($file);
    }
    //要写入的数版据
    $log_str = date("Y-m-d H:i:s"). " " . $logthis;
    if ($content) {
        //将每行的权数据放到数组中
        $arr = explode(PHP_EOL, $content);
        $offset = count($arr)-99;
        $offset = $offset >0 ? $offset : 0;
        $arr = array_slice($arr, $offset);
        $arr[] = $log_str;
        $content = implode(PHP_EOL, $arr);
    }else{
        $content = $log_str.PHP_EOL;
    }
    //写入文件
    $rs = file_put_contents($file, $content);
    /*if ($rs) {
        echo "log success !";
    }else{
        echo "log error !";
    }*/
  }
}

?>