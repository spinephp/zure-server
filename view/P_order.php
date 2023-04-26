<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowOrderController extends PageController{
    function process(){
        try{
            $this->forward('hjOrder.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowOrderController();
$controller->process();
?>