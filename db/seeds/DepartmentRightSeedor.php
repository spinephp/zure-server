<?php

use Phinx\Seed\AbstractSeed;

class DepartmentRightSeedor extends AbstractSeed
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
        for ($i = 0; $i < 40; $i++) {
            $data[] = [
                'departmentid'    => $faker->numberBetween(2,7),
                'name'  => $faker->text(20),
                'bit'  => $faker->numberBetween(0,32),
            ];
        }

        $this->insert('departmentright', $data);
    }
}
