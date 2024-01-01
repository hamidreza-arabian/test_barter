<?php

namespace App\Http\Controllers;

use App\Models\EstateTypeField;
use App\Http\Requests\StoreEstateTypeFieldRequest;
use App\Http\Requests\UpdateEstateTypeFieldRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class EstateTypeFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEstateTypeFieldRequest $request): JsonResponse
    {
        try {
            $res = [];
            $add_estates = $request->add_estates;
            foreach ($add_estates as $item) {
                $estate_type_id = $item['id_estate'];
                $estate_field_id = $item['id_fields'];
                foreach ($estate_field_id as $field) {
                    $res = EstateTypeField::create([
                        'estate_type_id' => $estate_type_id,
                        'estate_field_id' => $field,
                    ]);
                }
            }
            $delete_estates = $request->delete_estates;
            foreach ($delete_estates as $item) {
                $estate_type_id = $item['id_estate'];
                $estate_field_id = $item['id_fields'];
                foreach ($estate_field_id as $field) {
                    $res = EstateTypeField::where([
                        ['estate_type_id', '=', $estate_type_id],
                        ['estate_field_id', '=', $field],
                    ])->delete();
                }
            }
            return $this->successResponse($res, 201);
        }catch (Exception $exception){
            return $this->errorResponse(500, $exception->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(EstateTypeField $estateTypeField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEstateTypeFieldRequest $request, EstateTypeField $estateTypeField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EstateTypeField $estateTypeField)
    {
        //
    }
}
