<?php

use Phinx\Seed\AbstractSeed;

class OrderPackingTrayCartonSeeder extends AbstractSeed
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
            $traynum = $faker->numberBetween(1,5);
            $cartonnum = $faker->numberBetween(10,50);
            $numincarton = $faker->numberBetween(5,7);
            $data[] = [
                'orderid'     => $faker->numberBetween(1,10),
                'trayid'      => $faker->numberBetween(1,10),
                'traynum'     => $traynum,
                'cartonid'    => $faker->numberBetween(26,38),
                'cartonnum'   => $cartonnum,
                'numincarton' => $numincarton,
                'sumnum'      => $traynum*$cartonnum*$numincarton+$faker->numberBetween(0,$numincarton),
            ];
        }

        $this->insert('orderpackingtraycarton', $data);
    }
}
