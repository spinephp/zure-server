<?php
namespace woo\domain;
require_once("domain/domain.php");

class MeasureVersion extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("version","sysversion","overview","overview_en","content","content_en","address","size","sha256","mandatory"));
	}

	function setVersion($data_s){
		$this->objects["version"] = self::checkVersion($data_s);
	}

	function getVersion(){
		return $this->objects["version"];
	}

	function getVersion_real(){
		return self::versionToReal($this->objects["version"] );
	}

	function setSysversion($data_s){
		$this->objects["sysversion"] = self::checkVersion($data_s);
	}

	function getSysversion(){
		return $this->objects["sysversion"];
	}

	function getSysversion_real(){
		return self::versionToReal($this->objects["sysversion"]);
	}
    
	function setContent($name_s){
		$this->objects["content"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getContent(){
		return $this->objects["content"];
	}

	function setContent_en($name_s){
		$this->objects["content_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getContent_en(){
		return $this->objects["content_en"];
	}
    
	function setOverview($name_s){
		$this->objects["overview"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getOverview(){
		return $this->objects["overview"];
	}

	function setOverview_en($name_s){
		$this->objects["overview_en"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getOverview_en(){
		return $this->objects["overview_en"];
	}

	function setAddress($name_s){
		$this->objects["address"] = htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getAddress(){
		return $this->objects["address"];
	}

	function setSize($name_s){
		$this->objects["size"] = (double)$name_s;
	}

	function getSize(){
		return $this->objects["size"];
	}

	function setSha256($name_s){
		$this->objects["sha256"] =htmlentities($name_s,ENT_QUOTES,'UTF-8');
	}

	function getSha256(){
		return $this->objects["sha256"];
	}

	function setMandatory($name_s){
		$this->objects["mandatory"] = (bool)$name_s;
	}

	function getMandatory(){
		return $this->objects["mandatory"];
	}
}
?>