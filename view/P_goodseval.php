<?php
namespace woo\controller;
require_once("controller/PageController.php");

class GoodsEvalController extends PageController{
    function process(){
        try{
            $this->forward('hjGoodseval.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new GoodsEvalController();
$controller->process();
?>