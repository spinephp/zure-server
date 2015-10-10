<?php
/**
 * DeleteUpload文件上传 (ajax 调用)
 * @package command
 * @author  Liu xingming
 * @copyright 2014 Azuresky safe group
 * @return 如验证通过，返回包含用户信息的对象，否则返回指明验证失败信息的字符串
 */
namespace woo\controller;
require_once("controller/PageController.php");

class DeleteUploadController extends PageController{
	function process(){
		try
		{
			$request = $this->getRequest();
			$item = $request->getProperty("item");
			$pathfile = $item["file"];
			if(!strpos($pathfile,session_id()))
				throw new \woo\base\AppException("Invalid upload file!");
			if(file_exists($pathfile)){
				unlink($pathfile);
				$pos = strrpos($pathfile,"/");
				$path = substr($pathfile,0,$pos);
				if($this->isDirEmpty($path))
					rmdir($path);
			}
			echo json_encode(array("id"=>0,"msg"=>"Delete upload file success!"));
		}
		catch(Exception $e)
		{
			echo json_encode(array("id"=>-1,"msg"=>$e));
		}    
		catch(\woo\base\AppException $e){
			echo json_encode(array("id"=>-1,"msg"=>$e));
		}    
	}
	
	function isDirEmpty($dir){ 
		if($handle = opendir($dir)){  
			while($item = readdir($handle)){   
				if ($item != '.' && $item != '..')
					return false;  
				} 
			} 
			return true;
		}
	}

$controller = new DeleteUploadController();
$controller->process();
?>