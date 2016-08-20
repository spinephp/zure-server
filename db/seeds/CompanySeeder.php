<?php

use Phinx\Seed\AbstractSeed;

class CompanySeeder extends AbstractSeed
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
              'name'    => $faker->company,
              'address'  => $faker->address,
              'bank'     => $faker->company,
              'account'  => $faker->numerify("5###########"),
              'email'  => $faker->email,
              'www'  => $faker->url,
              'tel'  => $faker->tollFreePhoneNumber,
              'fax'  => $faker->tollFreePhoneNumber,
              'postcard'  => $faker->postcode,
              'duty'  => $faker->numerify("#################A")
         ];
      }

      $this->insert('company', $data);
    }
}
