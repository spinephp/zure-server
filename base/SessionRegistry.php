<?php
namespace woo\base;
require_once("base/Registry.php");
class SessionRegistry extends Registry{
	private static $instance;

	private function __construct(){
	    session_start();
	}

	static function instance(){
		if(!isset(self::$instance)){self::$instance = new self();}
		return self::$instance;
	}

	public function get($key){
		if(isset($_SESSION[$key])){
		    return $_SESSION[$key];
		}
		return null;
	}

	public function set($key,$val){
		$_SESSION[$key] = $val;
	}

	public function delete($key){
		unset($_SESSION[$key]);
	}

	public function destroy(){
	    session_destroy();
	}
    // function setComplex(Complex $complex){
		// self::instance()->set('complex',$complex);
	// }

    // function getComplex(){
		// return self::instance()->get('complex');
	// }
}
?>