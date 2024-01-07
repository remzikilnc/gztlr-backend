<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Weather extends Model
{
    use HasFactory;
    protected $fillable = [
        'city_id',
        'weather_status',
        'weather_description',
        'weather_icon',
        'sunrise',
        'sunset',
        'min_temp',
        'max_temp',
        'wind_speed',
        'wind_deg',
        'humidity',
        'applicable_date',
    ];
    protected $guarded = ['id'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
