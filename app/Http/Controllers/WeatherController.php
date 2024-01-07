<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Weather;

class WeatherController
{
    public function index(City $city)
    {
        $data = $city->weather()->get();
        return response()->ok($data);
    }
    public function show(City $city, Weather $weather)
    {
        $weather = $city->weather()->where('id', $weather->id)->first();
        return response()->ok(['weather' => $weather]);
    }
}
