<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowOrderInfoController extends PageController{
    function process(){
        try{
            $this->forward('hjOrderinfo.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowOrderInfoController();
$controller->process();
?>