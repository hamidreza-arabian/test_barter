<?php

namespace App\Http\Resources;

use App\Models\EstateField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstateTypeFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return EstateFieldResource
     */
    public function toArray(Request $request): EstateFieldResource
    {
        return new EstateFieldResource(EstateField::find($this->estate_field_id));
    }
}
