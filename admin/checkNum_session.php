<?php
//namespace woo\admin;
//require_once("base/SessionRegistry.php");
//$session = \woo\base\SessionRegistry::instance();
@session_start();
// 建立一幅 100X30 的图像
$im = imagecreate(50, 16);

$bg = imagecolorallocate($im, 255, 255, 255); //白色背景
$pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';    //字符池

// 把字符串写在图像左上角
$vcodes="";
for($i=0;$i<4;$i++)
{
 //$text= $pattern{rand(0,61)}; //生成随机数
 $text= $pattern{rand(0,9)}; //只生成字符池前面十个字符，即数字
 $textcolor = ImageColorAllocate($im, rand(100,255),rand(0,100),rand(100,255)); //字体随机颜色
 imagestring($im, 5, 2+$i*12, $i*rand(0,1), $text, $textcolor); //生成图片
 $vcodes.= $text;
}

//$session->set('saftcode',$vcodes);
$_SESSION['saftcode'] = $vcodes;

for($i=0;$i<100;$i++) //加入干扰象素
{ 
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand(0,100) , rand(0,30) , $randcolor);
} 

// 输出图像
header("Content-type: image/png");
imagepng($im);
?>

