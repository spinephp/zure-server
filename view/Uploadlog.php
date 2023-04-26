<?php
/**
 * 文件上传 (ajax 调用)
 * @package command
 * @author  Liu xingming
 * @copyright 2014 Azuresky safe group
 * @return 如验证通过，返回包含用户信息的对象，否则返回指明验证失败信息的字符串
 */
namespace woo\controller;
require_once("controller/PageController.php");
require_once("base/SessionRegistry.php");
require_once("resize-class.php");

class UploadlogController extends PageController{
  function process(){
    try
    {
	    $session = \woo\base\SessionRegistry::instance();
      $request = $this->getRequest();
      $headshot = null;
      $id = $request->getProperty("id");
      $name = null;
              $zipname = "i".sprintf("%08d", $id);
              $path = "images/log/";

	//   $path .= session_id()."/";
	  if(!is_dir($path)) mkdir($path,0777);
         $ext = ".zip";
	 
          $headshot = $zipname.$ext;
      $longname = $path.$headshot;
  	  if(file_exists($longname)){
        unlink($longname);
        }
        $p = file_get_contents('php://input');
        if(!is_null($p)){
          $pos = strpos($p,"}}");
          if($pos>0 && $pos<30)
          $p = substr($p,$pos+2);
        }
          file_put_contents($longname , $p);
      
      echo json_encode(array("image"=>$headshot ,"msg"=>"上传成功"));
    }
    catch(Exception $e)
    {
      echo json_encode(array("msg"=>$e));
    } 
}
}
$controller = new UploadlogController();
$controller->process();
?>