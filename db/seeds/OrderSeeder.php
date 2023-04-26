<?php

use Phinx\Seed\AbstractSeed;

class OrderSeeder extends AbstractSeed
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
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'code'    => $faker->date('yW').'00',
                'userid'    => $faker->numberBetween(2,10),
                'shipdate'    => [0,45][$faker->numberBetween(0,1)],
                'consigneeid'  => $faker->numberBetween(1,10),
                'paymentid'  => $faker->numberBetween(1,5),
                'transportid'  => $faker->numberBetween(1,3),
                'billtypeid'  => $faker->numberBetween(1,3),
                'billid'  => $faker->numberBetween(1,10),
                'billcontentid'  => $faker->numberBetween(1,4),
                'downpayment'  => [0,30,100][$faker->numberBetween(0,2)],
                'guarantee'  => $faker->numberBetween(0,10),
                'guaranteeperiod'  => $faker->numberBetween(0,3),
                'carriagecharge'  => $faker->randomFloat(2,0,5000),
                'returnnow'  => $faker->randomFloat(2,0,500),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'note'  => $faker->text,
                'stateid'  => $faker->numberBetween(0,3),
                'contract'  => $faker->numberBetween(0,1),
                'packingtypeid'  => $faker->numberBetween(1,6)
            ];
        }

        $this->insert('order', $data);
    }
}
