<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Politique', 'slug' => 'politique', 'description' => 'Actualité politique nationale et internationale'],
            ['name' => 'Économie', 'slug' => 'economie', 'description' => 'Nouvelles économiques et financières'],
            ['name' => 'Société', 'slug' => 'societe', 'description' => 'Faits de société et actualité locale'],
            ['name' => 'Culture', 'slug' => 'culture', 'description' => 'Art, musique et événements culturels'],
            ['name' => 'Sport', 'slug' => 'sport', 'description' => 'Actualités sportives'],
            ['name' => 'International', 'slug' => 'international', 'description' => 'Nouvelles du monde entier'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
