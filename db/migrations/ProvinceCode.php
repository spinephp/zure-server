<?php
    class provinceCode{
        private static $province_code = array(
            array('id'=>11, 'name'=>'北京市'),
            array('id'=>12, 'name'=>'天津市'),
            array('id'=>13, 'name'=>'河北省'),
            array('id'=>14, 'name'=>'山西省'),
            array('id'=>15, 'name'=>'内蒙古自治区'),
            array('id'=>21, 'name'=>'辽宁省'),
            array('id'=>22, 'name'=>'吉林省'),
            array('id'=>23, 'name'=>'黑龙江省'),
            array('id'=>31, 'name'=>'上海市'),
            array('id'=>32, 'name'=>'江苏省'),
            array('id'=>33, 'name'=>'浙江省'),
            array('id'=>34, 'name'=>'安徽省'),
            array('id'=>35, 'name'=>'福建省'),
            array('id'=>36, 'name'=>'江西省'),
            array('id'=>37, 'name'=>'山东省'),
            array('id'=>41, 'name'=>'河南省'),
            array('id'=>42, 'name'=>'湖北省'),
            array('id'=>43, 'name'=>'湖南省'),
            array('id'=>44, 'name'=>'广东省'),
            array('id'=>45, 'name'=>'广西壮族自治区'),
            array('id'=>46, 'name'=>'海南省'),
            array('id'=>50, 'name'=>'重庆市'),
            array('id'=>51, 'name'=>'四川省'),
            array('id'=>52, 'name'=>'贵州省'),
            array('id'=>53, 'name'=>'云南省'),
            array('id'=>54, 'name'=>'西藏自治区'),
            array('id'=>61, 'name'=>'陕西省'),
            array('id'=>62, 'name'=>'甘肃省'),
            array('id'=>63, 'name'=>'青海省'),
            array('id'=>64, 'name'=>'宁夏回族自治区'),
            array('id'=>65, 'name'=>'新疆维吾尔自治区'),
            array('id'=>71, 'name'=>'台湾省'),
            array('id'=>81, 'name'=>'香港'),
            array('id'=>82, 'name'=>'澳门'),
            array('id'=>91, 'name'=>'国外')
        );

        public static function getCode(){ return self::$province_code;}

    };
?>