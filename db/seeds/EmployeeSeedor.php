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
        for ($i = 0; $i < 9; $i++) {
            $data[] = [
                'userid'    => $i+2,
                'departmentid'    => $faker->numberBetween(2,7),
                'postids'    => sprintf("%02D",$faker->unique(true)->numberBetween(2,9)),
                'startdate'  => $faker->date('Y-m-d').' '.$faker->time('H:i:s'),
                'dateofbirth'  => $faker->date('Y-m-d'),
                'myright'  => $faker->numberBetween(0,0xffffffff),
            ];
        }

        $this->insert('employee', $data);
    }
}
