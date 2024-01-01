<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstateFieldTypeResource;
use App\Models\EstateFieldType;
use App\Http\Requests\StoreEstateFieldTypeRequest;
use App\Http\Requests\UpdateEstateFieldTypeRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class EstateFieldTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $estate_field_items = EstateFieldType::all();
            return $this->successResponse(EstateFieldTypeResource::collection($estate_field_items), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateFieldTypeRequest $request): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = EstateFieldType::create([
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
    public function show(EstateFieldType $estateFieldType): JsonResponse
    {
        try {
            return $this->successResponse(new EstateFieldTypeResource($estateFieldType), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateFieldTypeRequest $request, EstateFieldType $estateFieldType): JsonResponse
    {
        try {
            $title = trim($request->title);
            $res = $estateFieldType->update([
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
    public function destroy(EstateFieldType $estateFieldType): JsonResponse
    {
        try {
            $res = $estateFieldType->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
