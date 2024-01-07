<?php

namespace App\Repositories;

use App\Http\Resources\CityResource;
use App\Models\City;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CityRepository
{
    public function getAllCities()
    {
        $cities = QueryBuilder::for(City::class)
            ->allowedIncludes(['weather'])
            ->allowedFilters('name', AllowedFilter::exact('country_code'), AllowedFilter::scope('search', 'search'))
            ->defaultSort('country_code')
            ->allowedSorts(['id', 'name', 'country_code',])->get();

        return CityResource::collection($cities)->response()->getData(true);
    }
}
