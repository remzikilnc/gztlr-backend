<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    private Request $request;
    private City $city;
    private CityService $cityService;

    public function __construct(Request $request, City $city, CityService $cityService)
    {
        $this->request = $request;
        $this->city = $city;
        $this->cityService = $cityService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    //  $this->authorize('viewAny');
        $cities = $this->cityService->index();
        return response()->ok($cities);
    }

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
     // $this->authorize('view', $city);
        $this->cityService->show($city, $this->request->get('with', ''));
        return response()->ok(['city' => $city]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, City $city)
    {
       //todo update
    }
}
