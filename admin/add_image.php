<?php
//namespace woo\admin;
//require_once("base/SessionRegistry.php");
//$session = \woo\base\SessionRegistry::instance();
//@session_start();
// 建立一幅 100X30 的图像
const IMGWIDTH = 100;
const IMGHEIGHT = 100;
$im = imagecreate(IMGWIDTH, IMGHEIGHT);
$textcolor = ImageColorAllocate($im, 120,120,120); //字体随机颜色
imagerectangle($im,0,0,IMGWIDTH-1, IMGHEIGHT-1,$textcolor);

$bg = imagecolorallocate($im, 255, 255, 255); //白色背景
imagefilledrectangle($im,1,1,IMGWIDTH-2, IMGHEIGHT-2,$bg);

$lang = (int)$_GET["language"];
$pattern = array('Add image','添加图片')[$lang-1];
$font_size = 5;
// Create image width dependant on width of the string 
$width  = imagefontwidth($font_size)*strlen($pattern); 
// Set height to that of the font 
$height = imagefontheight($font_size); 
// Create the image pallette 

// 把字符串写在图像左上角
  //imagestring($im, $font_size, (IMGWIDTH-$width)/2, (IMGHEIGHT-$height)/2,$pattern, $textcolor); //生成图片
imagettftext($im, $font_size,0, (IMGWIDTH-$width)/2, (IMGHEIGHT-$height)/2, $textcolor, "simfang.ttf",$pattern); 
imagettftext($im, 13, 0,0, 0, $textcolor, "/woo/admin/simfang.ttf",$pattern); 
// 输出图像
header("Content-type: image/png");
imagepng($im);
?>

