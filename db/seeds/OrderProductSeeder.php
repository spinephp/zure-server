<?php

use Phinx\Seed\AbstractSeed;

class OrderProductSeeder extends AbstractSeed
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
            $number = [100,150,200,250,300,350][$faker->numberBetween(0,5)];
            $moldingnumber = $number+$faker->numberBetween(5,10);
            $drynumber = $moldingnumber-$faker->numberBetween(0,5);
            $firingnumber = $drynumber-$faker->numberBetween(0,5);
            $data[] = [
                'orderid'       => $faker->numberBetween(1,10),
                'proid'         => $faker->numberBetween(1,10),
                'number'        => $number,
                'price'         => $faker->numberBetween(90,100),
                'returnnow'     => $faker->numberBetween(0,2),
                'modlcharge'    => $faker->numberBetween(0,5000),
                'moldingnumber' => $moldingnumber,
                'drynumber'     => $drynumber,
                'firingnumber'  => $firingnumber,
                'packagenumber' => $number+$faker->numberBetween(0,$firingnumber-$number),
                'evalid'        => $faker->numberBetween(1,10),
                'feelid'        => $faker->numberBetween(1,10),
            ];
        }

        $this->insert('orderproduct', $data);
    }
}
