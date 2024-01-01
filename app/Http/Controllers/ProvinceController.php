<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use App\Http\Requests\StoreProvinceRequest;
use App\Http\Requests\UpdateProvinceRequest;
use Illuminate\Http\JsonResponse;
use \Exception;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $provinces = Province::all();
            return $this->successResponse(ProvinceResource::collection($provinces), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProvinceRequest $request): JsonResponse
    {
        try {
            $res = [];
            foreach ($request->all() as $item){
                $provinceData = $this->provinceInformation($item);
                $res = Province::create($provinceData);
            }
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province): JsonResponse
    {
        try {
            return $this->successResponse(new ProvinceResource($province), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProvinceRequest $request, Province $province): JsonResponse
    {
        try {
            $provinceData = $this->provinceInformation($request);
            $res = $province->update($provinceData);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Province $province): JsonResponse
    {
        try {
            $res = $province->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    private function provinceInformation($request): array
    {
        $id = (int)$request['provinceID'];
        $title = trim($request['name']);
        $slug = $request['slug'];
        return [
            'id' => $id,
            'title' => $title,
            'slug' => $slug,
        ];
    }
}
