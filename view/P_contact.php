<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowContactController extends PageController{
    function process(){
        try{
            $this->forward('hjContact.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowContactController();
$controller->process();
?>