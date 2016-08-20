<?php

use Phinx\Seed\AbstractSeed;

class NewsSeedor extends AbstractSeed
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
        $data = [];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'title'    => $faker1->sentence(3),
                'title_en'    => $faker->sentence(3),
                'content'    => $faker1->text($maxNbChars = 200),
                'content_en'    => $faker->text($maxNbChars = 200),
                'time'  => $faker->date('Y-m-d')
            ];
        }

        $this->insert('news', $data);
    }
}
