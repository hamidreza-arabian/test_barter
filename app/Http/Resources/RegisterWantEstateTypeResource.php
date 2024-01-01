<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\EstateType;
use App\Models\Province;
use App\Models\Region;
use App\Models\RegisterWantEstateField;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterWantEstateTypeResource extends JsonResource
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
            'estate_type' => new EstateTypeResource(EstateType::find($this->estate_type_id)),
            'barter_type' => $this->barter_type,
            'barter_price' => $this->barter_price,
            'province' => new ProvinceResource(Province::where(['id' => $this->province_id])->first()),
            'city' => new CityResource(City::where(['id' => $this->city_id])->first()),
//            'district' => new DistrictResource(District::where(['id' => $this->district_id])->first()),
            'region' => new DistrictResource(Region::where(['id' => $this->region_id])->first()),
            'fields' => RegisterWantEstateFieldResource::collection(
                RegisterWantEstateField::where(['register_want_estate_type_id' => $this->id])->get()
            ),
        ];
    }
}
