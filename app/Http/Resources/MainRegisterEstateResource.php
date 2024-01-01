<?php

namespace App\Http\Resources;

use App\Models\RegisterEstate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainRegisterEstateResource extends JsonResource
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
            'type' => $this->type,
            'status' => $this->status,
            'customer' => new UserResource(User::where(['id' => $this->customer_id])->first()),
            'employee' => new UserResource(User::where(['id' => $this->employee_id])->first()),
            'created_at' => strtotime($this->created_at)*1000,
            'updated_at' => strtotime($this->updated_at)*1000,
            'estates' => RegisterEstateResource::collection($this->registerEstate)
        ];
    }
}
