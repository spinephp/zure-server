<?php

use Phinx\Seed\AbstractSeed;

class ProductCareSeeder extends AbstractSeed
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
                'userid'  => $faker->numberBetween(11,20),
                'proid'  => $faker->numberBetween(1,10),
                'currencyid'  => $faker->numberBetween(1,2),
                'exchangerate'  => $faker->randomFloat(2,6,7),
                'price'  => $faker->randomFloat(2,100,500),
                'date'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'label'  => $faker->sentence($faker->numberBetween(1,2))
            ];
        }

        $this->insert('productcare', $data);
    }
}
