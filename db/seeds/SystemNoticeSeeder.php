<?php

use Phinx\Seed\AbstractSeed;

class SystemNoticeSeeder extends AbstractSeed
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
                'type'  => ['E', 'G'][$faker->numberBetween(0,1)],
                'content'  => $faker->text(100),
                'time'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'readstate'  => $faker->numberBetween(0,1)
            ];
        }

        $this->insert('systemnotice', $data);
    }
}
