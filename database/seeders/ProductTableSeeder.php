<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index)  {
            Product::create([
                'name' => $faker->city,
                'price' => $faker->numberBetween($min = 100, $max = 5000),
                'description'=> $faker->paragraph($nb =2),
                'image'=> '/images/'.$faker->image('public/images',640,480, null, false)
            ]);
        }
    }
}
