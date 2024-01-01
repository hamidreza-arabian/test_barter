<?php

namespace App\Http\Resources;

use App\Models\EstateFieldItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstateFieldItemResource extends JsonResource
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
        ];
    }
}
