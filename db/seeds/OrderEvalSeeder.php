<?php

use Phinx\Seed\AbstractSeed;

class OrderEvalSeeder extends AbstractSeed
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
                'userid'    => $faker->numberBetween(2,10),
                'orderid'  => $faker->unique()->numberBetween(1,10),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'content'  => $faker->text,
                'award'  => $faker->numberBetween(1,5)
            ];
        }

        $this->insert('order_eval', $data);
    }
}
