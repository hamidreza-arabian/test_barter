<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterWantEstateType extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];



    public function wantFields(){
        return $this->hasMany(RegisterWantEstateField::class);
    }
    public function province(){
        return $this->belongsTo(Province::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function region(){
        return $this->belongsTo(Region::class);
    }
    public function estateType(){
        return $this->belongsTo(EstateType::class);
    }
    public function employee(){
        return $this->belongsTo(User::class);
    }



}
