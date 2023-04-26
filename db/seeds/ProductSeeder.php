<?php

use Phinx\Seed\AbstractSeed;

class ProductSeeder extends AbstractSeed
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
            $classid = $faker->numberBetween(7,17);
            $data[] = [
                'classid'  => $classid,
                'length'  => $faker->randomFloat(2,200,900),
                'width'  => $faker->randomFloat(2,200,900),
                'think'  => $faker->randomFloat(2,10,150),
                'unitlen'  => ['mm','"'][$faker->numberBetween(0,1)],
                'unitwid'  => ['mm','"'][$faker->numberBetween(0,1)],
                'unitthi'  => ['mm','"'][$faker->numberBetween(0,1)],
                'picture'  => sprintf("%d_%d.png",$classid,$i+1),
                'sharp'  => $faker->numberBetween(1,8),
                'unit'  => ['片','块'][$faker->numberBetween(0,1)],
                'weight'  => $faker->randomFloat(2,200,900),
                'homeshow'  => ['Y','N'][$faker->numberBetween(0,1)],
                'price'  => $faker->randomFloat(2,80,900),
                'returnnow'  => $faker->randomFloat(2,2,10),
                'evalintegral'  => $faker->numberBetween(1,3000),
                'feelintegral'  => $faker->numberBetween(1,3000),
                'amount'  => $faker->numberBetween(100,400),
                'cansale'  => ['Y','N'][$faker->numberBetween(0,1)],
                'physicoindex'  => 1,
                'chemicalindex'  => 1,
                'status'  => ['O', 'D', 'P', 'N'][$faker->numberBetween(0,3)],
                'note'  => $faker->text,
            ];
        }

        $this->insert('product', $data);
    }
}
