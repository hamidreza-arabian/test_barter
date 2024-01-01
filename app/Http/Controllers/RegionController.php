<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionResource;
use App\Models\Region;
use App\Http\Requests\StoreRegionRequest;
use App\Http\Requests\UpdateRegionRequest;
use Illuminate\Http\JsonResponse;
use Exception;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $regions = Region::all();
            return $this->successResponse(RegionResource::collection($regions), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRegionRequest $request): JsonResponse
    {
        try {
            $res = [];
            foreach ($request->all() as $item){
                $regionData = $this->regionInformation($item);
                $res = Region::create($regionData);
            }
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, int $city_id): JsonResponse
    {
        try {
            return $this->successResponse(RegionResource::collection(
                Region::where('city_id', $city_id)->get()
            ), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRegionRequest $request, Region $region): JsonResponse
    {
        try {
            $regionData = $this->regionInformation($request);
            $res = $region->update($regionData);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region): JsonResponse
    {
        try {
            $res = $region->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    private function regionInformation($request): array
    {
        $city_id = (int)$request['city_id'];
        $id = (int)$request['id'];
        $title = $request['title'];
        return [
            'id' => $id,
            'city_id' => $city_id,
            'title' => $title,
        ];
    }
}
