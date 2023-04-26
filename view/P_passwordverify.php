<?php
namespace woo\controller;
require_once("controller/PageController.php");

class PasswordVerifyController extends PageController{
    function process(){
		try{
			switch($this->getRequest()->getProperty('type')){
			case 'ResetPassword':
				$this->forward('hjResetPassword.php');
				break;
			}
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new PasswordVerifyController();
$controller->process();
?>
