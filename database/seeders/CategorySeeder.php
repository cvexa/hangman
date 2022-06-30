<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['category' => 'Градове', 'image' => 'cities.jpg'],
            ['category' => 'Предмети', 'image' => 'items.jpg'],
            ['category' => 'Видове Риби', 'image' => 'fishes.jpeg']
        ];
        Category::insert($categories);
    }
}
