<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'customer'=>[
                'full_name' => $this->customer_name,
                'phone_number' => $this->customer_phone_number,
            ],
            'employee'=>[
                'full_name' => $this->employee_name,
                'phone_number' => $this->employee_phone_number,
            ],
            'asset'=>[
                'title_provinces' => $this->title_provinces,
                'title_cities' => $this->title_cities,
                'title_regions' => $this->title_regions,
                'address' => $this->address,
                'price' => $this->price,
                'estate_type' => $this->estate_title,
                'main_register_estate_id' => $this->main_register_estate_id
            ]
        ];
    }
}
