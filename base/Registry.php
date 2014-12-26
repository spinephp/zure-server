<?php
namespace woo\base;

abstract class Registry {
    abstract protected function get($key);
    abstract protected function set($key,$val);
}

class AppException extends \Exception{
	private $error;

	function __construct($msg){
		parent::__construct($msg);
    $this->error = $msg;
	}

	function getError(){
		return $this->error;
	}
}
?>