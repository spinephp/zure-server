<?php
namespace woo\domain;

require_once("mapper/Collection.php");
require_once("mapper/HeaderCollection.php");
require_once("mapper/NewsCollection.php");
require_once("mapper/Mapper.php");
class HelperFactory{
	static private $collection = array();
	static private $mapper = array();
	static function getCollection($type){
		$mode = StrToLower(trim(str_replace("woo\\domain\\","",$type)));
		if(!isset(self::$collection[$mode])){
			if($mode=="header"){
				self::$collection[$mode] = new \woo\mapper\HeaderCollection();
			}else if($mode=="news"){
				self::$collection[$mode] = new \woo\mapper\NewsCollection();
			}
		}
        return self::$collection[$mode];
	}

	static function getFinder($type){
		$mode = StrToLower(trim(str_replace("woo\\domain\\","",$type)));
		if(!isset(self::$mapper[$mode])){
			if($mode=="header"){
				self::$mapper[$mode] = new \woo\mapper\HeaderMapper();
			}else if($mode=="News"){
				self::$mapper[$mode] = new \woo\mapper\NewsMapper();
			}
		}
        return self::$mapper[$mode];
	}
}
?>