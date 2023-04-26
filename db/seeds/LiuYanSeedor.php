<?php

use Phinx\Seed\AbstractSeed;

class LiuYanSeedor extends AbstractSeed
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
        $faker = Faker\Factory::create('zh_CN');
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'name'    => $faker->name(),
                'company'    => $faker->company,
                'address'    => $faker->address,
                'title'    => $faker->text(50),
                'email'    => $faker->email,
                'tel'    => $faker->phoneNumber,
                'content'  => $faker->text,
                'time'  => $faker->date('Y-m-d'),
                'ip'  => $faker->ipv4
            ];
        }

        $this->insert('liuyan', $data);
    }
}
