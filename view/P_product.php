<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowProductsController extends PageController{
    function process(){
        try{
            $this->forward('hjProduct.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowProductsController();
$controller->process();
?>