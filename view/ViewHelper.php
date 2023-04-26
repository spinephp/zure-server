<?php
namespace woo\view;
require_once("base/RequestRegistry.php");
require_once("base/SessionRegistry.php");
class VH {
	static function getRequest() {
		return \woo\base\RequestRegistry::getRequest();
	}

	static function getSession() {
		return \woo\base\SessionRegistry::instance();
	}
	
	static function ShowHeader($title,$css,$js){
		$request = self::getRequest(); // Ccontroller caches this
		$header = $request->getObject("header"); // Command caches this
		require_once("Show_Header.php");
	}
}
?>