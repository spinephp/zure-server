<?php
namespace woo\controller;
require_once("base/ApplicationRegistry.php");
require_once("base/RequestRegistry.php");
require_once("controller/ControllerMap.php");
require_once("command/command.php");

class ApplicationHelper{
    private static $instance;
    private $config = "data/woo_options.xml";

    private function __construct(){}

    static function instance(){
        if(!self::$instance){
            self::$instance = new self();
        }
        return self::$instance;
    }

    function init(){
        $dsn = \woo\base\ApplicationRegistry::getDSN();
        if(!is_null($dsn)){
            return;
        }
        $this->getOptions();
    }
 
    private function getOptions(){
        $this->ensure(file_exists($this->config),"Could not fund options file");
        $options = SimpleXml_load_file($this->config);
        //print get_class($options);
        $dsn = (string)$options->dsn;
		$dbuser = (string)$options->user;
		$dbpwd = (string)$options->pwd;
        $this->ensure($dsn,"No DSN found");
        \woo\base\ApplicationRegistry::setDSN($dsn);
        \woo\base\ApplicationRegistry::setDBUser($dbuser);
        \woo\base\ApplicationRegistry::setDBPwd($dbpwd);
        //设置其它值
        $map = new ControllerMap();

        foreach($options->control->view as $default_view){
            $stat_str = trim($default_view['status']);
            $status = \woo\command\Command::statuses($stat_str);
            $map->addView('default',$status,(string)$default_view);
        }

        foreach($options->control->command as $command){
			if(isset($command->classroot)){
				$map->addClassroot((string)$command['name'],(string)$command->classroot['name']);
			}
			if(isset($command->view)){
			    $map->addView((string)$command['name'],0,(string)$command->view);
            }
			if(isset($command->status)){
                $stat_str = trim($command->status['value']);
                $status = \woo\command\Command::statuses($stat_str);
				if(isset($command->status->forward)){
		            $map->addForward((string)$command['name'],$status,(string)$command->status->forward);
				}
            }
        }

        // ......省略了更多解析代码
        \woo\base\ApplicationRegistry::setControllerMap($map);
    }

    private function ensure($expr,$message){
        if(!$expr){
            throw new \woo\base\AppException($message);
        }
    }
}
?>