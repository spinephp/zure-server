<?php
/**
* ���ܣ�PHPͼƬˮӡ (ˮӡ֧��ͼƬ������) 
* ������ 
*       $groundImage     ����ͼƬ������Ҫ��ˮӡ��ͼƬ����ֻ֧��GIF,JPG,PNG��ʽ�� 
*       $waterPos        ˮӡλ�ã���10��״̬��0Ϊ���λ�ã� 
*                       1Ϊ���˾���2Ϊ���˾��У�3Ϊ���˾��ң� 
*                       4Ϊ�в�����5Ϊ�в����У�6Ϊ�в����ң� 
*                       7Ϊ�׶˾���8Ϊ�׶˾��У�9Ϊ�׶˾��ң� 
*       $waterImage      ͼƬˮӡ������Ϊˮӡ��ͼƬ����ֻ֧��GIF,JPG,PNG��ʽ�� 
*       $waterText       ����ˮӡ������������ΪΪˮӡ��֧��ASCII�룬��֧�����ģ� 
*       $fontSize        ���ִ�С��ֵΪ1��2��3��4��5��Ĭ��Ϊ5�� 
*       $textColor       ������ɫ��ֵΪʮ��������ɫֵ��Ĭ��Ϊ#CCCCCC(�׻�ɫ)�� 
*       $fontfile        ttf�����ļ�����������������ˮӡ�����塣ʹ��windows���û���ϵͳ�̵�Ŀ¼��
*                       ����*.ttf���Եõ�ϵͳ�а�װ�������ļ�������Ҫ���ļ�������վ���ʵ�Ŀ¼��,
*                       Ĭ���ǵ�ǰĿ¼��arial.ttf��
*       $xOffset         ˮƽƫ����������Ĭ��ˮӡ����ֵ�����ϼ������ֵ��Ĭ��Ϊ0�������������ˮӡ��
*                       ��ˮƽ�����ϵı߾࣬�����������ֵ,�磺2 ���ʾ��Ĭ�ϵĻ�����������2����λ,-2 ��ʾ����������λ
*       $yOffset         ��ֱƫ����������Ĭ��ˮӡ����ֵ�����ϼ������ֵ��Ĭ��Ϊ0�������������ˮӡ��
*                       ����ֱ�����ϵı߾࣬�����������ֵ,�磺2 ���ʾ��Ĭ�ϵĻ�����������2����λ,-2 ��ʾ����������λ
* ����ֵ��
*        0   ˮӡ�ɹ�
*        1   ˮӡͼƬ��ʽĿǰ��֧��
*        2   Ҫˮӡ�ı���ͼƬ������
*        3   ��Ҫ��ˮӡ��ͼƬ�ĳ��Ȼ��ȱ�ˮӡͼƬ����������С���޷�����ˮӡ
*        4   �����ļ�������
*        5   ˮӡ������ɫ��ʽ����ȷ
*        6   ˮӡ����ͼƬ��ʽĿǰ��֧��
* �޸ļ�¼��
*         
* ע�⣺Support GD 2.0��Support FreeType��GIF Read��GIF Create��JPG ��PNG 
*       $waterImage �� $waterText ��ò�Ҫͬʱʹ�ã�ѡ����֮һ���ɣ�����ʹ�� $waterImage�� 
*       ��$waterImage��Чʱ������$waterString��$stringFont��$stringColor������Ч�� 
*       ��ˮӡ���ͼƬ���ļ����� $groundImage һ���� 
* ���ߣ�������
* ���ڣ�2007-4-28
* ˵�������������longware�ĳ����д���ɡ� 
*/ 
namespace woo\view;
function imageWaterMark($groundImage,$waterPos=0,$waterImage="",$waterText="",$fontSize=12,$textColor="#CCCCCC", $fontfile='./mnjcy.TTF',$xOffset=0,$yOffset=0) 
{ 
   $isWaterImage = FALSE; 
     //��ȡˮӡ�ļ� 
     if(!empty($waterImage) && file_exists($waterImage)) { 
         $isWaterImage = TRUE; 
         $water_info = getimagesize($waterImage); 
         $water_w     = $water_info[0];//ȡ��ˮӡͼƬ�Ŀ� 
         $water_h     = $water_info[1];//ȡ��ˮӡͼƬ�ĸ� 

         switch($water_info[2])   {    //ȡ��ˮӡͼƬ�ĸ�ʽ  
             case 1:$water_im = imagecreatefromgif($waterImage);break; 
             case 2:$water_im = imagecreatefromjpeg($waterImage);break; 
             case 3:$water_im = imagecreatefrompng($waterImage);break; 
             default:return 1; 
         } 
     } 

     //��ȡ����ͼƬ 
     if(!empty($groundImage) && file_exists($groundImage)) { 
         $ground_info = getimagesize($groundImage); 
         $ground_w     = $ground_info[0];//ȡ�ñ���ͼƬ�Ŀ� 
         $ground_h     = $ground_info[1];//ȡ�ñ���ͼƬ�ĸ� 

         switch($ground_info[2]) {    //ȡ�ñ���ͼƬ�ĸ�ʽ  
             case 1:$ground_im = imagecreatefromgif($groundImage);break; 
             case 2:$ground_im = imagecreatefromjpeg($groundImage);break; 
             case 3:$ground_im = imagecreatefrompng($groundImage);break; 
             default:return 1; 
         } 
     } else { 
         return 2; 
     } 

     //ˮӡλ�� 
     if($isWaterImage) { //ͼƬˮӡ  
         $w = $water_w; 
         $h = $water_h; 
         $label = "ͼƬ��";
         } else {  
     //����ˮӡ 
        if(!file_exists($fontfile))return 4;
         $temp = imagettfbbox($fontSize,0,$fontfile,$waterText);//ȡ��ʹ�� TrueType ������ı��ķ�Χ 
         $w = $temp[2] - $temp[6]; 
         $h = $temp[3] - $temp[7]; 
         unset($temp); 
     } 
     if( ($ground_w < $w) || ($ground_h < $h) ) { 
         return 3; 
     } 
     switch($waterPos) { 
         case 0://��� 
             $posX = rand(0,($ground_w - $w)); 
             $posY = rand(0,($ground_h - $h)); 
             break; 
         case 1://1Ϊ���˾��� 
             $posX = 0; 
             $posY = 0; 
             break; 
         case 2://2Ϊ���˾��� 
             $posX = ($ground_w - $w) / 2; 
             $posY = 0; 
             break; 
         case 3://3Ϊ���˾��� 
             $posX = $ground_w - $w; 
             $posY = 0; 
             break; 
         case 4://4Ϊ�в����� 
             $posX = 0; 
             $posY = ($ground_h - $h) / 2; 
             break; 
         case 5://5Ϊ�в����� 
             $posX = ($ground_w - $w) / 2; 
             $posY = ($ground_h - $h) / 2; 
             break; 
         case 6://6Ϊ�в����� 
             $posX = $ground_w - $w; 
             $posY = ($ground_h - $h) / 2; 
             break; 
         case 7://7Ϊ�׶˾��� 
             $posX = 0; 
             $posY = $ground_h - $h; 
             break; 
         case 8://8Ϊ�׶˾��� 
             $posX = ($ground_w - $w) / 2; 
             $posY = $ground_h - $h; 
             break; 
         case 9://9Ϊ�׶˾��� 
             $posX = $ground_w - $w; 
             $posY = $ground_h - $h; 
             break; 
         default://��� 
             $posX = rand(0,($ground_w - $w)); 
             $posY = rand(0,($ground_h - $h)); 
             break;     
     } 

     //�趨ͼ��Ļ�ɫģʽ 
     imagealphablending($ground_im, true); 

     if($isWaterImage) { //ͼƬˮӡ 
         imagecopy($ground_im, $water_im, $posX + $xOffset, $posY + $yOffset, 0, 0, $water_w,$water_h);//����ˮӡ��Ŀ���ļ�         
     } else {//����ˮӡ
         if( !empty($textColor) && (strlen($textColor)==7) ) { 
             $R = hexdec(substr($textColor,1,2)); 
             $G = hexdec(substr($textColor,3,2)); 
             $B = hexdec(substr($textColor,5)); 
         } else { 
           return 5;
         } 
         imagettftext ( $ground_im, $fontSize, 0, $posX + $xOffset, $posY + $h + $yOffset, imagecolorallocate($ground_im, $R, $G, $B), $fontfile, $waterText);
     } 

     //����ˮӡ���ͼƬ 
     @unlink($groundImage); 
     switch($ground_info[2]) {//ȡ�ñ���ͼƬ�ĸ�ʽ 
         case 1:imagegif($ground_im,$groundImage);break; 
         case 2:imagejpeg($ground_im,$groundImage);break; 
         case 3:imagepng($ground_im,$groundImage);break; 
         default: return 6; 
     } 

     //�ͷ��ڴ� 
     if(isset($water_info)) unset($water_info); 
     if(isset($water_im)) imagedestroy($water_im); 
     unset($ground_info); 
     imagedestroy($ground_im); 
     //
     return 0;
}
?>
