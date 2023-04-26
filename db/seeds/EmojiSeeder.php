<?php

use Phinx\Seed\AbstractSeed;

class EmojiSeeder extends AbstractSeed
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
              'name'    => $faker->userName,
              'name_en'    => $faker->userName,
              'introduction'  => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
              'introduction_en'  => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
              'stand'  => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
              'stand_en'  => $faker->paragraph($nbSentences = 1, $variableNbSentences = true),
              'alias'     => "{$faker->userName};{$faker->userName}",
              'alias_en'  => "{$faker->userName};{$faker->userName}",
              'code'  => $faker->numerify('#####'),
              'shortcode'  => $faker->word,
              'related'  => "{$faker->numerify('##')};{$faker->numerify('##')};{$faker->numerify('##')}"
         ];
      }

      $this->insert('emoji', $data);
    }
}
