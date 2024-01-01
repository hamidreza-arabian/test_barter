<?php

namespace App\Http\Resources;

use App\Models\EstateFieldItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstateFieldResource extends JsonResource
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
            'type' => $this->estate_field_type_id,
            'want_type' => $this->want_estate_field_type_id,
            'items' => EstateFieldItemResource::collection(
                EstateFieldItem::where('estate_field_id', $this->id)->get()
            ),
        ];
    }
}
