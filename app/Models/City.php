<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\BaseRelationableModel;

class City extends BaseRelationableModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'country_code',
        'lat',
        'lon',
        'timezone',
        'timezone_offset',
    ];

    public function weather(): HasMany
    {
        return $this->hasMany(Weather::class);
    }


    public function getCountryCodeAttribute(): int
    {
/*        if ($this->attributes['country_code'] < 10) {
            return 0 . $this->attributes['country_code'];
        }*/
        return $this->attributes['country_code'];
    }

    public function scopeSearch($query, $value)
    {
        return $query
            ->where('name', 'like', '%' . $value . '%')
            ->orWhere('country_code', 'like', '%' . $value . '%');
    }


    /**
     * @return array
     * Array key is relation name
     * Array value is permission name
     * Array value can be blank if no permission is required
     * @example 'relation_name' => 'permission_name'
     */
    public static function getLoadableRelations(): array
    {
        return [
            'weather' => '',
        ];
    }
}
