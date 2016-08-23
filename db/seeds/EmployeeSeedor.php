<?php

use Phinx\Seed\AbstractSeed;

class EmployeeSeedor extends AbstractSeed
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
                'userid'    => $i+1,
                'departmentid'    => $faker->numberBetween(2,7),
                'startdate'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'dateofbirth'  => $faker->date('Y-m-d'),
                'myright'  => $faker->numberBetween(0,0xffffffff),
            ];
        }

        $this->insert('employee', $data);
    }
}
