<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::firstOrCreate(['name' => 'Cafe'], ['description' => '']);
        Category::firstOrCreate(['name' => 'Chicken'], ['description' => '']);
    }
}
