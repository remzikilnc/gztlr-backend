<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => $this->name,
            'country_code' => $this->country_code,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'timezone' => $this->timezone,
            'weather' => WeatherResource::collection($this->whenLoaded('weather')),
        ];
    }
}
