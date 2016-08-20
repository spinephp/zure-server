<?php

use Phinx\Seed\AbstractSeed;

class CustomAccountSeeder extends AbstractSeed
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
            $in = $faker->randomFloat(8,0,100000);
            $out = $in>0? 0:$faker->randomFloat(8,100,100000);
            $data[] = [
                'userid'    => $faker->numberBetween(11,21),
                'in'    => $in,
                'out'  => $out,
                'lock'     => ($in+$out)*$faker->numberBetween(0,5)/100,
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'note'  => $faker->text,
            ];
        }

        $this->insert('customaccount', $data);
    }
}
