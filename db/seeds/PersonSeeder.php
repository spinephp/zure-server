<?php

use Phinx\Seed\AbstractSeed;

class PersonSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $faker = Faker\Factory::create();
        $faker1 = Faker\Factory::create('zh_CN'); // create a French faker
        $data = [];
        $City = array(11=>"北京",12=>"天津",13=>"河北",14=>"山西",15=>"内蒙古",21=>"辽宁",22=>"吉林",23=>"黑龙江",31=>"上海",32=>"江苏",33=>"浙江",34=>"安徽",35=>"福建",36=>"江西",37=>"山东",41=>"河南",42=>"湖北",43=>"湖南",44=>"广东",45=>"广西",46=>"海南",50=>"重庆",51=>"四川",52=>"贵州",53=>"云南",54=>"西藏",61=>"陕西",62=>"甘肃",63=>"青海",64=>"宁夏",65=>"新疆",71=>"台湾",81=>"香港",82=>"澳门",91=>"国外");
        $code = array_keys($City);
        $n = count($code)-1;
        for ($i = 0; $i < 20; $i++) {
            $province = $faker->randomElement($code);
            $city = $faker->numberBetween(1,7);
            $zone = $faker->numberBetween(1,7);
            $county = sprintf("%02d%02d%02d",$province,$city,$zone);
            $preid = sprintf("%s%s%03d",$county,$faker->date('Ymd'),$faker->numberBetween(1,999));
            $salt = substr(md5($faker->userName),0,7);
            $data[] = [
                'username'    => $faker->userName,
                'pwd'    => $salt.sha1($salt."82340137"),
                'email'    => $faker1->email,
                'active'    => ['Y','N'][$faker->numberBetween(0,1)],
                'companyid'  => $faker->numberBetween(1,10),
                'name'  => $faker1->name,
                'nick'  => $faker1->name,
                'sex'  => ['M','F'][$faker->numberBetween(0,1)],
                'country'  => $i<10? 48:(($i % 5)? 48:$faker->numberBetween(1,251)),
                'county'  => $county,
                'address'  => $faker1->address,
                'mobile'  => $faker1->phoneNumber,
                'tel'  => $faker1->e164PhoneNumber,
                'qq'  => $faker->numberBetween(1,9).$faker->numerify("#########"),
                'identitycard'  => $preid.$this->getVerifyBit($preid),
                'picture'  => sprintf("u%08d.png",$i+2),
                'registertime'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'lasttime'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'times'  => $faker->randomDigit,
                'hash'  => NULL
            ];
        }

        $this->insert('person', $data);
    }
 
    // 计算身份证校验码，根据国家标准GB 11643-1999
    private function getVerifyBit($idcard_base)
    {
        if(strlen($idcard_base) != 17)
        {
            return false;
        }
        //加权因子
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        //校验码对应值
        $verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4','3', '2');
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++)
        {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];
        return $verify_number;
    }
}
