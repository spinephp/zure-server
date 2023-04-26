<?php
namespace woo\domain;
require_once("domain/domain.php");

class Carriagecharge extends DomainObject{

	function __construct($array){
		parent::__construct($array,array("grade1","grade2","grade3","grade4","grade5"));
	}

	function setGrade1($grade_s){
		$this->objects["grade1"] = (float)$grade_s;
	}

	function getGrade1(){
		return $this->objects["grade1"];
	}

	function setGrade2($grade_s){
		$this->objects["grade2"] = (float)$grade_s;
	}

	function getGrade2(){
		return $this->objects["grade2"];
	}

	function setGrade3($grade_s){
		$this->objects["grade3"] = (float)$grade_s;
	}

	function getGrade3(){
		return $this->objects["grade3"];
	}

	function setGrade4($grade_s){
		$this->objects["grade4"] = (float)$grade_s;
	}

	function getGrade4(){
		return $this->objects["grade4"];
	}

	function setGrade5($grade_s){
		$this->objects["grade5"] = (float)$grade_s;
	}

	function getGrade5(){
		return $this->objects["grade5"];
	}
  
  function getCharge($weight){
    $w = (float)$weight;
    if( $w < 1000)
      $grage = "grade1";
    else if( $w <5000)
      $grade = "grade2";
    else if ($w <10000)
      $grade = "grade3";
    else if ($w <20000)
      $grade = "grade4";
    else
      $grade = "grade5";
    return $this->objects[$grade]*$w;
  }
}
?>