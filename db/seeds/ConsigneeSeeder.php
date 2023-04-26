<?php

use Phinx\Seed\AbstractSeed;

class ConsigneeSeeder extends AbstractSeed
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
        $faker1 = Faker\Factory::create('zh_CN');
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $province = $faker->numberBetween(31,37);
            $city = '0'.$faker->numberBetween(1,9);
            $zone = '0'.$faker->numberBetween(1,9);
            $data[] = [
                'customid'    => $faker->numberBetween(1,10),
                'name'    => $i % 4? $faker1->name:$faker->name,
                'country'  => $faker->numberBetween(1,251),
                'province'     => $province,
                'city'  => $city,
                'zone'  => $zone,
                'address'  => $faker->streetAddress,
                'email'  => $faker->email,
                'mobile'  => $faker1->phoneNumber,
                'tel'  => $faker->e164PhoneNumber,
                'postcard'  => $faker1->postcode,
            ];
        }

        $this->insert('consignee', $data);
    }
}
