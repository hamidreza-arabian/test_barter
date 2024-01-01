<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use App\Http\Requests\StoreCityRequest;
use App\Http\Requests\UpdateCityRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use \Exception;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $cities = City::all();
            return $this->successResponse(CityResource::collection($cities), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCityRequest $request): JsonResponse
    {
        try {
            $res = [];
            foreach ($request->all() as $item) {
                $cityData = $this->cityInformation($item);
                $res = City::create($cityData);
            }
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $province_id): JsonResponse
    {
        try {
            return $this->successResponse(CityResource::collection(
                City::where('province_id', $province_id)->get()
            ), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCityRequest $request, City $city): JsonResponse
    {
        try {
            $cityData = $this->cityInformation($request);
            $res = $city->update($cityData);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city): JsonResponse
    {
        try {
            $res = $city->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    private function cityInformation($request): array
    {
        $province_id = (int)$request['provinceID'];
        $id = (int)$request['cityID'];
        $title = $request['name'];
        $slug = $request['slug'];
        return [
            'id' => $id,
            'province_id' => $province_id,
            'title' => $title,
            'slug' => $slug,
        ];
    }
}
