<?php

namespace Database\Seeders;

use App\Models\City;
use Database\Default\Country\GetStaticCountries;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = app(GetStaticCountries::class)->getCities();
        foreach ($cities as $city) {
            $city['slug'] = Str::slug($city['name']);
            app(City::class)->create($city);
        }
    }
}
