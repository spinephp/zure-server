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
    @ini_set('session.use_trans_sid','0');
    @ini_set("session.use_only_cookies",1);
    @ini_set("session.use_cookies",1);

class UploadController extends PageController{
  function process(){
    try
    {
	    $session = \woo\base\SessionRegistry::instance();
      $request = $this->getRequest();
      $headshot = null;
      $name = null;
      foreach($_FILES as $key => $value)
      {
          //print_r ($_FILES[$key]); echo "<br>";
          list($width, $height, $type, $attr) = getimagesize($value["tmp_name"]);
          switch(substr($key,0,7)){
            case 'userimg': // 头像
              $imgName = substr($key,7);
              $path = "images/user/";
              $width = $height = 100;
              $option = 'crop';
              break;
            case 'goodimg': // 产品图片
              $imgName = substr($key,7);
              $path = "images/good/";
              $width = 453;
              $height = 267;
              $option = 'crop';
              break;
            case 'maskimg': // 水印图片
              $imgName = $key;
              $path = "images/";
              $option = 'exact';
              break;
            case 'feelimg': // 晒单图片
              $imgName = substr($key,7);
              $path = "images/good/feel/";
              $option = 'crop';
              break;
          }
          $temp = "images/temp/";
         $ext = substr($value['name'],strrpos($value['name'],'.'));
          $headshot = $imgName.$ext;
          if(!move_uploaded_file($value["tmp_name"], $temp.$headshot))
          	throw new \woo\base\AppException("File moving failure!");
         
          $resizeObj = new \resize($temp.$headshot);
 
          // *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
          $resizeObj -> resizeImage($width,$height, $option);
          //my_image_resize($value["tmp_name"],$resizeImg, $width,$height);
          //$headshot = $imgName.".png";
          $resizeObj -> saveImage($path.$imgName.".png", 100);
          unlink($temp.$headshot);
      }
      echo json_encode(array("image"=>$imgName.".png","msg"=>"上传成功"));
    }
    catch(Exception $e)
    {
      echo json_encode(array("msg"=>$e));
    }    
    catch(\woo\base\AppException $e){
      echo json_encode(array("msg"=>$e));
    }    
}

  function my_image_resize($src_file, $dst_file, $dst_width=32, $dst_height=32) { 
      if($dst_width <1 || $dst_height <1) { 
          echo "params width or height error !"; 
          exit(); 
      } 
      if(!file_exists($src_file)) { 
          echo $src_file . " is not exists !"; 
          exit(); 
      } 

      $type=exif_imagetype($src_file); 
      $support_type=array(IMAGETYPE_JPEG , IMAGETYPE_PNG , IMAGETYPE_GIF); 

      if(!in_array($type, $support_type,true)) { 
          echo "this type of image does not support! only support jpg , gif or png"; 
          exit(); 
      } 

      switch($type) { 
          case IMAGETYPE_JPEG : 
              $src_img=imagecreatefromjpeg($src_file); 
              break; 
          case IMAGETYPE_PNG : 
              $src_img=imagecreatefrompng($src_file);         
              break; 
          case IMAGETYPE_GIF : 
              $src_img=imagecreatefromgif($src_file); 
              break; 
          default: 
              echo "Load image error!"; 
              exit(); 
      } 
      $src_w=imagesx($src_img); 
      $src_h=imagesy($src_img); 
      $ratio_w=1.0 * $dst_width/$src_w; 
      $ratio_h=1.0 * $dst_height/$src_h; 
      if ($src_w<=$dst_width && $src_h<=$dst_height) {
          $x = ($dst_width-$src_w)/2;
          $y = ($dst_height-$src_h)/2;
          $new_img=imagecreatetruecolor($dst_width,$dst_height); 
          imagecopy($new_img,$src_img,$x,$y,0,0,$dst_width,$dst_height); 
          switch($type) { 
              case IMAGETYPE_JPEG : 
                  imagejpeg($new_img,$dst_file,100);
                  break; 
              case IMAGETYPE_PNG : 
                  imagepng($new_img,$dst_file); 
                  break; 
              case IMAGETYPE_GIF : 
                  imagegif($new_img,$dst_file); 
                  break; 
              default: 
                  break; 
          } 
      } else { 
          $dstwh = $dst_width/$dst_height; 
          $srcwh = $src_w/$src_h;
          if ($ratio_w <= $ratio_h) {
              $zoom_w = $dst_width; 
              $zoom_h = $zoom_w*($src_h/$src_w); 
          } else { 
              $zoom_h = $dst_height; 
              $zoom_w = $zoom_h*($src_w/$src_h); 
          } 

          $zoom_img=imagecreatetruecolor($zoom_w, $zoom_h); 
          imagecopyresampled($zoom_img,$src_img,0,0,0,0,$zoom_w,$zoom_h,$src_w,$src_h); 
          $new_img=imagecreatetruecolor($dst_width,$dst_height); 
          $x = ($dst_width-$zoom_w)/2;
          $y = ($dst_height-$zoom_h)/2+1;
          imagecopy($new_img,$zoom_img,$x,$y,0,0,$dst_width,$dst_height);
          switch($type) { 
              case IMAGETYPE_JPEG : 
                  imagejpeg($new_img,$dst_file,100);
                  break; 
              case IMAGETYPE_PNG : 
                  imagepng($new_img,$dst_file); 
                  break; 
              case IMAGETYPE_GIF : 
                  imagegif($new_img,$dst_file); 
                  break; 
              default: 
                  break; 
          }
      }
  }
}

$controller = new UploadController();
$controller->process();
?>