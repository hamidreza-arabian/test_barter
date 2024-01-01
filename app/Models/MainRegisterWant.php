<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainRegisterWant extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function wantEstate(){
        return $this->hasMany(RegisterWantEstateType::class);
    }
}
