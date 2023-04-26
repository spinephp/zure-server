<?php

use Phinx\Seed\AbstractSeed;

class EvalReplySeedor extends AbstractSeed
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
                'evalid'    => $faker->numberBetween(2,10),
                'userid'    => $faker->numberBetween(2,10),
                'parentid'    => $faker->numberBetween(0,10),
                'content'  => $faker->sentence(3),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s')
            ];
        }

        $this->insert('evalreply', $data);
    }
}
