<?php

use Phinx\Seed\AbstractSeed;

class CustomSeeder extends AbstractSeed
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
                'userid'    => $faker->numberBetween(11,21),
                'type'    => ['P','U'][$faker->numberBetween(0,1)],
                'emailstate'  => ['Y','N'][$faker->numberBetween(0,1)],
                'mobilestate'     => ['Y','N'][$faker->numberBetween(0,1)],
                'accountstate'  => ['E','D'][$faker->numberBetween(0,1)],
                'ip'  => $faker->ipv4,
                'emailcode'  => NULL,
                'integral'  => $faker->numberBetween(0,10000),
            ];
        }

        $this->insert('custom', $data);

    }
}
