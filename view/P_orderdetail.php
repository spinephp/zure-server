<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowOrderDetailController extends PageController{
    function process(){
        try{
            $this->forward('hjOrderdetail.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowOrderDetailController();
$controller->process();
?>