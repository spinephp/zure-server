<?php

use Phinx\Seed\AbstractSeed;

class OrderComplainSeeder extends AbstractSeed
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
                'orderid'  => $faker->numberBetween(1,10),
                'content'  => $faker->text,
                'type'  => $faker->numberBetween(1,5),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'status'  => ['S', 'D', 'C'][$faker->numberBetween(0,2)],
                'note'  => $faker->text(40)
            ];
        }

        $this->insert('ordercomplain', $data);
    }
}
