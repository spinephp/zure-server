<?php
namespace woo\controller;
require_once("controller/PageController.php");

class ShowNewsController extends PageController{
    function process(){
        try{
            $this->forward('hjNews.php');
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new ShowNewsController();
$controller->process();
?>