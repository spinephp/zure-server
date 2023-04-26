<?php
namespace woo\controller;
require_once("controller/PageController.php");

class InvoiceController extends PageController{
    function process(){
        try{
            $this->forward('invoice.word.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new InvoiceController();
$controller->process();
?>