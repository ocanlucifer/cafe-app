<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $items = [];

        for ($i = 0; $i < 100; $i++) {
            // Set type_id: 70% chance of type_id = 2, 30% chance of type_id = 1
            $type_id = ($i < 70) ? 2 : 1;

            // If type_id = 2, set category_id to 6 (Bahan Makanan/Minuman)
            $category_id = ($type_id === 2) ? 6 : rand(1, 5);

            // Create an item with stock = 0, price, active status
            $items[] = [
                'name' => $faker->word,                         // Random name for the item
                'category_id' => $category_id,                   // Category ID: if type_id = 2, category_id = 6, else random between 1 and 5
                'type_id' => $type_id,                           // Random type_id (70% = 2, 30% = 1)
                'price' => $faker->randomFloat(2, 10, 500),      // Random price between 10 and 500
                'stock' => 0,                                    // Set stock to 0
                'active' => $faker->boolean,                     // Random active status (true or false)
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert the generated items into the database
        DB::table('items')->insert($items);
    }
}
