<?php

namespace App\Http\Resources;

use App\Models\EstateField;
use App\Models\EstateTypeField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstateTypeWithFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'fields' => EstateTypeFieldResource::collection(
                EstateTypeField::where('estate_type_id', $this->id)->get()
            ),
        ];
    }
}
