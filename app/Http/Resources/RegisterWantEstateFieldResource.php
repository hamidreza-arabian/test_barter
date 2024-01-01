<?php

namespace App\Http\Resources;

use App\Models\EstateField;
use App\Models\RegisterWantEstateItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterWantEstateFieldResource extends JsonResource
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
            'from_text' => $this->from_text,
            'to_text' => $this->to_text,
            'field' => new EstateFieldShowResource(EstateField::where(['id' => $this->estate_field_id])->first()),
            'items' => RegisterEstateItemResource::collection(
                RegisterWantEstateItem::where(['register_want_estate_field_id' => $this->id])->get()
            ),
        ];
    }
}
