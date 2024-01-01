<?php

namespace App\Http\Controllers;

use App\Http\Resources\DistrictResource;
use App\Models\District;
use App\Http\Requests\StoreDistrictRequest;
use App\Http\Requests\UpdateDistrictRequest;
use Illuminate\Http\JsonResponse;
use \Exception;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $districts = District::all();
            return $this->successResponse(DistrictResource::collection($districts), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDistrictRequest $request): JsonResponse
    {
        try {
            $res = [];
            foreach ($request->all() as $item) {
                $districtData = $this->districtInformation($item);
                $res = District::create($districtData);
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
            return $this->successResponse(DistrictResource::collection(
                District::where('city_id' ,$city_id)->get()
            ), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDistrictRequest $request, District $district): JsonResponse
    {
        try {
            $districtData = $this->districtInformation($request);
            $res = $district->update($districtData);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(District $district): JsonResponse
    {
        try {
            $res = $district->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    private function districtInformation($request): array
    {
        $id = (int)$request['districtID'];
        $city_id = (int)$request['cityID'];
        $title = trim($request['name']);
        return [
            'id' => $id,
            'city_id' => $city_id,
            'title' => $title,
        ];
    }
}
