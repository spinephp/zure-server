<?php
namespace woo\controller;

require_once("base/Registry.php");
require_once("controller/ControllerMap.php");
require_once("controller/Request.php");

class AppController{
    private static $base_cmd;
    private static $default_cmd;
    private $controllerMap;
    private $invoked = array();

    function __construct(ControllerMap $map){
        $this->controllerMap = $map;
        if(!self::$base_cmd){
            self::$base_cmd = new \ReflectionClass("\\woo\\command\\Command");
            self::$default_cmd = new \woo\command\DefaultCommand();
        }
    }

    function getView(Request $req){
        $view = $this->getResource($req,"View");
        return $view;
    }

    function getForward(Request $req){
        $forward = $this->getResource($req,"Forward");
        if($forward){
            $req->setProperty('cmd',$forward);
        }
        return $forward;
    }

    private function getResource(Request $req,$res){
        // 得到前一个命令及其执行状态
        $cmd_str = $req->getProperty('cmd');
        $previous = $req->getLastCommand();
		if($previous){
            $status = $previous->getStatus();
		}else{
			$status = 0;
		}
        if(!$status){ $status = 0;}
        $acquire = "get$res";
        // 得到前一个命令的资源及其状态
        $resource = $this->controllerMap->$acquire($cmd_str,$status);
        // 查找命令并且状态为 0 的资源
        if(!$resource){
            $resource = $this->controllerMap->$acquire($cmd_str,0);
        }

        // 或者'default'命令和命令状态
        if(!$resource){
            $resource = $this->controllerMap->$acquire('default',$status);
        }
        
        // 其它情况获取'default'失败主，状态为 0
        if(!$resource){
            $resource = $this->controllerMap->$acquire('default',0);
        }
        return $resource;
    }

    function getCommand(Request $req){
        $previous = $req->getLastCommand();
		if(!$previous){
            // 这是本次请求调用的第一个命令
            $cmd = $req->getProperty('cmd');
			if(!$cmd){// 如果无法得到命令，则使用默认命令
                $req->setProperty('cmd','default');
                return self::$default_cmd;
            }
        }else{
            // 之前已执行过一个命令
            $cmd = $this->getForward($req);
            if(!$cmd) {return null;}
        }

        // 在$cmd变量中保存者命令名称，并将其解析为Command对象
        $cmd_obj = $this->resolveCommand($cmd);
        if(!$cmd_obj){
            throw new \woo\base\AppException("could n't resolve '$cmd'");
        }

        $cmd_class = get_class($cmd_obj);
		//$cmd_class = str_replace("woo\\command\\","",$cmd_class);
		if(isset($this->invoked[$cmd_class])){
			throw new \woo\base\AppException("circular forwarding");
		}
        $this->invoked[$cmd_class] = 1;

        // 返回Command对象
        return $cmd_obj;
    }

    function resolveCommand($cmd){
        $classroot = $this->controllerMap->getClassroot($cmd);
        $filepath = "command/$classroot.php";
        $classname = "\\woo\\command\\{$classroot}";
        if(file_exists($filepath)){
            require_once("$filepath");
        }else{
          $classname = "\\woo\\command\\RESTCommand";// 如何进行安全检查
        }
        if(class_exists($classname)){
            $cmd_class = new \ReflectionClass($classname);
            if($cmd_class->isSubClassOf(self::$base_cmd)){
                return $cmd_class->newInstance();
            }
        }
        return null;
    }
}
?>