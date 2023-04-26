<?php
namespace woo\controller;
require_once("controller/PageController.php");

class OfficeController extends PageController{
    function process(){
        try{
            $this->forward('hjOffice.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new OfficeController();
$controller->process();
?>