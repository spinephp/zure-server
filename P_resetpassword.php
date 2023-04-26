<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ResetPasswordController extends PageController{
    function process(){
        try{
            $this->forward('hjResetPassword.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ResetPasswordController();
$controller->process();
?>
