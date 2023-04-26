<?php

use Phinx\Seed\AbstractSeed;

class OrderDownPaymentSeeder extends AbstractSeed
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
                'orderid'  => $faker->unique()->numberBetween(1,10),
                'downpayment'  => $faker->randomFloat(2,0,50000),
                'date'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
            ];
        }

        $this->insert('order_downpayment', $data);
    }
}
