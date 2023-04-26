<?php

use Phinx\Seed\AbstractSeed;

class ProductEvalSeeder extends AbstractSeed
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
                'label'  => $faker->numberBetween(4,70),
                'useideas'  => $faker->text,
                'star'  => $faker->numberBetween(0,5),
                'integral'  => $faker->numberBetween(0,2),
                'date'  => $faker->date('Y-m-d'),
                'useful'  => $faker->numberBetween(0,500),
                'status'  => ['W', 'A', 'S'][$faker->numberBetween(0,2)]
            ];
        }

        $this->insert('producteval', $data);
    }
}
