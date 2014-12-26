<?php
namespace woo\controller;
require_once("../controller/PageController.php");

class AddVenueController extends PageController{
    function process(){
        try{
            $request = $this->getRequest();
            $name = $request->getProperty('venue_name');
            if(is_null($request->getProperty('submitted'))){
                $request->addFeedback("choose a name for the venue");
                $this->forward('add_venue.php');
            }else if(is_null($name)){
                $request->addFeedback("name is a required field");
                $this->forward('add_venue.php');
            }

            // 创建对象便可将它添加到数据库
            $venue = new \woo\domain\Venue(null,$name);
            $this->forward("ListVenues.php");
        }catch(Exception $e){
            $this->forward('error.php');
        }
    }
}

$controller = new AddVenueController();
$controller->process();
?>