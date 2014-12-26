<?php
namespace woo\domain;
require_once("domain/domain.php");

class Chemicalindex extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("sic","si3n4","sio2","si","fe2o3","cao","al2o3"));
	}

	function setSic($sic_s){
		$this->objects["sic"] = (float)$sic_s;
	}

	function getSic(){
		return $this->objects["sic"];
	}

	function setSi3n4($si3n4_s){
		$this->objects["si3n4"] = (float)$si3n4_s;
	}

	function getSi3n4(){
		return $this->objects["si3n4"];
	}

	function setSio2($sio2_s){
		$this->objects["sio2"] = (float)$sio2_s;
	}

	function getSio2(){
		return $this->objects["sio2"];
	}

	function setSi($si_s){
		$this->objects["si"] = (float)$si_s;
	}

	function getSi(){
		return $this->objects["si"];
	}

	function setFe2o3($fe2o3_s){
		$this->objects["fe2o3"] = (float)$fe2o3_s;
	}

	function getFe2o3(){
		return $this->objects["fe2o3"];
	}

	function setCao($cao_s){
		$this->objects["cao"] = (float)$cao_s;
	}

	function getCao(){
		return $this->objects["cao"];
	}

	function setAl2o3($al2o3_s){
		$this->objects["al2o3"] = (float)$al2o3_s;
	}

	function getAl2o3(){
		return $this->objects["al2o3"];
	}
}
?>