<?php

namespace App\Http\Resources;

use App\Models\EstateField;
use App\Models\RegisterEstateItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterEstateFieldResource extends JsonResource
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
            'text' => $this->text,
            'estate_field_id' => $this->estate_field_id,
            'field' => new EstateFieldShowResource(EstateField::where(['id' => $this->estate_field_id])->first()),
            'items' => RegisterEstateItemResource::collection(
                RegisterEstateItem::where(['register_estate_field_id' => $this->id])->get()
            ),
        ];
    }
}
