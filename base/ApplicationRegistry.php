<?php
namespace woo\base;
require_once("base/Registry.php");
require_once("controller/ControllerMap.php");
require_once("controller/AppController.php");
class ApplicationRegistry extends Registry{
	private static $instance;
	private $freezedir = "data";
	private static $ControllerMap;
	private static $appcontroller=null;
	private $values = array();
	private $mtimes = array();

	private function __construct(){
		$path = $this->freezedir . DIRECTORY_SEPARATOR . "tempdb";
		if(file_exists($path)){
		    unlink($path);
		}
	}

	static function instance(){
		if(!isset(self::$instance)){self::$instance = new self();}
		return self::$instance;
	}

	protected function get($key){
		$path = $this->freezedir . DIRECTORY_SEPARATOR . "tempdb";
		if(file_exists($path)){
			clearstatcache();
			$mtime = filemtime($path);
			if(!isset($this->mtimes[$key])){ $this->mtimes[$key] = 0;}
			if($mtime>$this->mtimes[$key]){
				$data = file_get_contents($path);
				$this->mtimes[$key] = $mtime;
				return ($this->values = \unserialize($data));
			}
		}
		if(isset($this->values[$key])){
			return $this->values[$key];
		}
		return null;
	}

	protected function set($key,$val){
		$this->values[$key] = $val;
		$path = $this->freezedir . DIRECTORY_SEPARATOR ."tempdb";
		while(file_exists($path))
			file_put_contents($path,serialize($this->values));
		$this->mtimes[$key] = time();
	}

    static function setControllerMap(\woo\controller\ControllerMap $map){
		self::$ControllerMap = $map;
		if(is_null(self::$appcontroller)){
			self::$appcontroller = new \woo\controller\AppController($map);
		}
	}

    static function AppController(){
		return self::$appcontroller;
	}

	static function getDSN(){
		return self::instance()->get('dsn');
	}

	static function setDSN($dsn){
		return self::instance()->set('dsn',$dsn);
	}

	static function getDBUser(){
		return self::instance()->get('dbuser');
	}

	static function setDBUser($user){
		return self::instance()->set('dbuser',$user);
	}

	static function getDBPwd(){
		return self::instance()->get('dbpwd');
	}

	static function setDBPwd($pwd){
		return self::instance()->set('dbpwd',$pwd);
	}
	
	function brower(){  
		$brower = $_SERVER['HTTP_USER_AGENT'];  
		if(preg_match('/360SE/',$brower)){  
			$brower["360se"] = null;  
		}elseif(preg_match('/Maxthon/',$brower)){  
			$brower["Maxthon"] = null;  
		}elseif(preg_match('/Tencent/',$brower)){  
			$brower["Tencent Brower"] = null;  
		}elseif(preg_match('/Green/',$brower)){  
			$brower["Green Brower"] = null;  
		}elseif(preg_match('/baidu/',$brower)){  
			$brower["baidu"] = null;  
		}elseif(preg_match('/TheWorld/',$brower)){  
			$brower["The World"] = null;  
		}elseif(preg_match('/MetaSr/',$brower)){  
			$brower["Sogou Brower"] = null;  
		}elseif(preg_match('/Firefox/',$brower)){  
			$brower["Firefox"] = null;  
		}elseif(preg_match('/MSIE\s6\.0/',$brower)){  
			$brower["IE"] = "6.0";  
		}elseif(preg_match('/MSIE\s7\.0/',$brower)){  
			$brower["IE"] = "7.0";  
		}elseif(preg_match('/MSIE\s8\.0/',$brower)){  
			$brower["IE"] = "8.0";  
		}elseif(preg_match('/MSIE\s9\.0/',$brower)){  
			$brower["IE"] = "9.0";  
		}elseif(preg_match('/Netscape/',$brower)){  
			$brower["Netscape"] = null;  
		}elseif(preg_match('/Opera/',$brower)){  
			$brower["Opera"] = null;  
		}elseif(preg_match('/Chrome/',$brower)){  
			$brower["Chrome"] = null;  
		}elseif(preg_match('/Gecko/',$brower)){  
			$brower["Gecko"] = null;  
		}elseif(preg_match('/Safari/',$brower)){  
			$brower["Safari"] = null;  
		}else $brower["Unknow"] = null;  
		return $brower;  
	}
}
?>