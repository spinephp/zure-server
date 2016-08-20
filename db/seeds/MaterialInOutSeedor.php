<?php

use Phinx\Seed\AbstractSeed;

class MaterialInOutSeedor extends AbstractSeed
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
                'materialid'    => $faker->numberBetween(1,66),
                'number'    => $faker->randomFloat(2,-10000,100000),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'note'  => $faker->text($maxNbChars = 100)
            ];
        }

        $this->insert('materialinout', $data);
    }
}
