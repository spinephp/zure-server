<?php

use Phinx\Seed\AbstractSeed;

class AnnouncementSeeder extends AbstractSeed
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
      for ($i = 0; $i < 100; $i++) {
          $data[] = [
              'title'    => $faker->text(50),
              'content'  => $faker->text(200),
              'time'     => $faker->date('Y-m-d').' '.$faker->time('H:i:s')
          ];
      }

      $this->insert('announcement', $data);
    }
}
