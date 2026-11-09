<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['name' => 'Côte d’Ivoire', 'code' => 'CI'],
            ['name' => 'Burkina Faso', 'code' => 'BF'],
            ['name' => 'Ghana', 'code' => 'GH'],
            ['name' => 'France', 'code' => 'FR'],
            ['name' => 'Sénégal', 'code' => 'SN'],
            ['name' => 'Mali', 'code' => 'ML'],
            ['name' => 'Nigeria', 'code' => 'NG'],
            ['name' => 'Togo', 'code' => 'TG'],
            ['name' => 'Bénin', 'code' => 'BJ'],
            ['name' => 'Guinée', 'code' => 'GN'],
        ] as $country) {
            Country::updateOrCreate(
                ['code' => $country['code']],
                [...$country, 'slug' => Str::slug($country['name']), 'is_active' => true]
            );
        }
    }
}
