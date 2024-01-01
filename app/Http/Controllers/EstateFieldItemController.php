<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstateFieldItemResource;
use App\Models\EstateFieldItem;
use App\Http\Requests\StoreEstateFieldItemRequest;
use App\Http\Requests\UpdateEstateFieldItemRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class EstateFieldItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $estate_field_items = EstateFieldItem::all();
            return $this->successResponse(EstateFieldItemResource::collection($estate_field_items), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateFieldItemRequest $request): JsonResponse
    {
        try {
            $title = trim($request->title);
            $estate_field_id = $request->estate_field_id;
            $res = EstateFieldItem::create([
                'title' => $this->arabicToPersian($title),
                'estate_field_id' => $estate_field_id,
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EstateFieldItem $estateFieldItem): JsonResponse
    {
        try {
            return $this->successResponse(new EstateFieldItemResource($estateFieldItem), 200);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateFieldItemRequest $request, EstateFieldItem $estateFieldItem): JsonResponse
    {
        try {
            $title = trim($request->title);
            $estate_field_id = $request->estate_field_id;
            $res = $estateFieldItem->update([
                'title' => $this->arabicToPersian($title),
                'estate_field_id' => $estate_field_id,
            ]);
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateFieldItem $estateFieldItem): JsonResponse
    {
        try {
            $res = $estateFieldItem->delete();
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }
}
