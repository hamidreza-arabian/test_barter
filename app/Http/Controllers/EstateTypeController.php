<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstateFieldTypeResource;
use App\Http\Resources\EstateTypeResource;
use App\Http\Resources\EstateTypeWithFieldResource;
use App\Http\Resources\WantEstateTypeWithFieldResource;
use App\Models\EstateFieldType;
use App\Models\EstateType;
use App\Http\Requests\StoreEstateTypeRequest;
use App\Http\Requests\UpdateEstateTypeRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class EstateTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $estate_types = EstateType::all();
            return $this->successResponse(EstateTypeResource::collection($estate_types), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateTypeRequest $request): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = EstateType::create([
                'title' => $this->arabicToPersian($title),
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EstateType $estateType): JsonResponse
    {
        try {
            return $this->successResponse(new EstateTypeResource($estateType), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateTypeRequest $request, EstateType $estateType): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = $estateType->update([
                'title' => $this->arabicToPersian($title),
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateType $estateType): JsonResponse
    {
        try {
            $res = $estateType->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    public function get_estates_with_fields(): JsonResponse
    {
        try {
            $estate_types = EstateType::all();
            return $this->successResponse([
                    'estates' => EstateTypeWithFieldResource::collection($estate_types),
                    'field_types' => EstateFieldTypeResource::collection(EstateFieldType::all())
                ], 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
    public function get_want_estates_with_fields(): JsonResponse
    {
        try {
            $estate_types = EstateType::all();
            return $this->successResponse([
                    'estates' => WantEstateTypeWithFieldResource::collection($estate_types),
                    'field_types' => EstateFieldTypeResource::collection(EstateFieldType::all())
                ], 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
