<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\District;
use App\Models\EstateType;
use App\Models\Province;
use App\Models\Region;
use App\Models\RegisterEstateField;
use App\Models\RegisterWantEstateType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterEstateResource extends JsonResource
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
            'main_register_estate_id' => $this->main_register_estate_id,
            'customer' => new UserResource(User::where(['id' => $this->customer_id])->first()),
            'employee' => new UserResource(User::where(['id' => $this->employee_id])->first()),
            'province' => new ProvinceResource(Province::where(['id' => $this->province_id])->first()),
            'city' => new CityResource(City::where(['id' => $this->city_id])->first()),
//            'district' => new DistrictResource(District::where(['id' => $this->district_id])->first()),
            'region' => new DistrictResource(Region::where(['id' => $this->region_id])->first()),
            'address' => $this->address,
            'price' => $this->price,
            'estate_type' => new EstateTypeResource(EstateType::find($this->estate_type_id)),
            'created_at' => strtotime($this->created_at)*1000,
            'updated_at' => strtotime($this->updated_at)*1000,
            'status' => $this->status,
            'fields' => RegisterEstateFieldResource::collection(
                RegisterEstateField::where(['register_estate_id' => $this->id])->get()
            ),
            'want' => RegisterWantEstateTypeResource::collection(
                RegisterWantEstateType::where(['main_register_estate_id' => $this->main_register_estate_id])->get()
            )
        ];
    }
}
