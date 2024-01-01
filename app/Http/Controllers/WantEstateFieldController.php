<?php

namespace App\Http\Controllers;

use App\Models\EstateTypeField;
use App\Models\WantEstateField;
use App\Http\Requests\StoreWantEstateFieldRequest;
use App\Http\Requests\UpdateWantEstateFieldRequest;
use Illuminate\Http\JsonResponse;
use Exception;

class WantEstateFieldController extends Controller
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
    public function store(StoreWantEstateFieldRequest $request): JsonResponse
    {
        try {
            $res = [];
            $add_estates = $request->add_estates;
            foreach ($add_estates as $item) {
                $estate_type_id = $item['id_estate'];
                $estate_field_id = $item['id_fields'];
                foreach ($estate_field_id as $field) {
                    $res = WantEstateField::create([
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
                    $res = WantEstateField::where([
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
    public function show(WantEstateField $wantEstateField)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWantEstateFieldRequest $request, WantEstateField $wantEstateField)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WantEstateField $wantEstateField)
    {
        //
    }
}
