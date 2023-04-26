<?php

use Phinx\Seed\AbstractSeed;

class DryLineSender extends AbstractSeed
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
        $faker1 = Faker\Factory::create('zh_CN'); // create a French faker
        $data = [
            ['lineno' => 0,'temperature' => 50, 'time' => 5],
            ['lineno' => 0,'temperature' => 50, 'time' => 16],
            ['lineno' => 0,'temperature' => 90, 'time' => 5],
            ['lineno' => 0,'temperature' => 90, 'time' => 16],
            ['lineno' => 0,'temperature' => 125, 'time' => 3],
            ['lineno' => 0,'temperature' => 125, 'time' => 16],
            ['lineno' => 0,'temperature' => 60, 'time' => -10]
        ];

        $this->insert('dryline', $data);
    }
}
