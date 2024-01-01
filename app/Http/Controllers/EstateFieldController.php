<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstateFieldResource;
use App\Models\EstateField;
use App\Http\Requests\StoreEstateFieldRequest;
use App\Http\Requests\UpdateEstateFieldRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class EstateFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $estate_fields = EstateField::all();
            return $this->successResponse(EstateFieldResource::collection($estate_fields), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateFieldRequest $request): JsonResponse
    {
        try {
            $title = trim($request->title);
            $estate_field_type_id = $request->estate_field_type_id;
            $res = EstateField::create([
                'title' => $this->arabicToPersian($title),
                'estate_field_type_id' => $estate_field_type_id,
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EstateField $estateField): JsonResponse
    {
        try {
            return $this->successResponse(new EstateFieldResource($estateField), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateFieldRequest $request, EstateField $estateField): JsonResponse
    {
        try {
            $title = trim($request->title);
            $estate_field_type_id = $request->estate_field_type_id;
            $res = $estateField->update([
                'title' => $this->arabicToPersian($title),
                'estate_field_type_id' => $estate_field_type_id,
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateField $estateField): JsonResponse
    {
        try {
            $res = $estateField->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
