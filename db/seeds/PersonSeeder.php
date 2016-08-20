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
        for ($i = 0; $i < 20; $i++) {
            $province = $faker->numberBetween(11,45);
            $city = $faker->numberBetween(1,7);
            $zone = $faker->numberBetween(1,7);
            $county = sprintf("%02d%02d%02d",$province,$city,$zone);
            $data[] = [
                'username'    => $faker->userName,
                'pwd'    => sha1($faker->password),
                'email'    => $faker->email,
                'active'    => ['Y','N'][$faker->numberBetween(0,1)],
                'companyid'  => $faker->numberBetween(1,10),
                'name'  => $faker1->name,
                'nick'  => $faker1->name,
                'sex'  => ['M','F'][$faker->numberBetween(0,1)],
                'country'  => $i<10? 48:(($i % 5)? 48:$faker->numberBetween(1,251)),
                'county'  => $county,
                'address'  => $faker1->address,
                'mobile'  => $faker1->phoneNumber,
                'tel'  => $faker->tollFreePhoneNumber,
                'qq'  => $faker->numerify("##########"),
                'identitycard'  => sprintf("%s%s%04d",$county,$faker->date('Ymd'),$faker->numberBetween(1,9999)),
                'picture'  => sprintf("u%08d.png",$i+2),
                'registertime'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'lasttime'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'times'  => $faker->randomDigit,
                'hash'  => NULL
            ];
        }

        $this->insert('person', $data);
    }
}
