<?php

use Phinx\Seed\AbstractSeed;

class ProductUseSeeder extends AbstractSeed
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
                'title'  => $faker->text(40),
                'content'  => $faker->text,
                'date'  => $faker->date('Y-m-d'),
                'status'  => ['W', 'A', 'S'][$faker->numberBetween(0,2)]
            ];
        }

        $this->insert('productuse', $data);
    }
}
