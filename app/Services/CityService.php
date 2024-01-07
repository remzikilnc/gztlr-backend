<?php

namespace App\Services;

use App\Http\Resources\CityResource;
use App\Models\City;
use App\Repositories\CityRepository;

class CityService extends BaseService
{
    public function index()
    {
        return app(CityRepository::class)->getAllCities();
    }

    public function show(City $city, string $requestedRelations): CityResource
    {
        $loadableRelations = $this->filterLoadableRelations($requestedRelations, City::class);
        $city->load($loadableRelations);
        return new CityResource($city);
    }
}
