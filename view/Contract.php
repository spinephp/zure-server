<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ContractController extends PageController{
    function process(){
        try{
            $this->forward('contract.edit.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ContractController();
$controller->process();
?>