<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name' => 'Makanan',
        ]);
        Category::create([
            'name' => 'Snack',
        ]);
        Category::create([
            'name' => 'Minuman',
        ]);
        Category::create([
            'name' => 'Paket Makanan',
        ]);
        Category::create([
            'name' => 'Paket Snack',
        ]);
        Category::create([
            'name' => 'Bahan',
        ]);
    }
}
