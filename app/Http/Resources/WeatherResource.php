<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->city_id,
            'weather_status' => $this->weather_status,
            'weather_description' => $this->weather_description,
            'weather_icon' => $this->weather_icon,
            'sunrise' => $this->sunrise,
            'sunset' => $this->sunset,
            'min_temp' => $this->min_temp,
            'max_temp' => $this->max_temp,
            'wind_speed' => $this->wind_speed,
            'wind_deg' => $this->wind_deg,
            'humidity' => $this->humidity,
            'applicable_date' => $this->applicable_date,
            'created_at' => $this->created_at,
        ];
    }
}
