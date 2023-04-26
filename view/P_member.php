<?php
namespace woo\controller;
require_once("controller/PageController.php");

class MemberController extends PageController{
    function process(){
        try{
            $this->forward('hjMember.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new MemberController();
$controller->process();
?>