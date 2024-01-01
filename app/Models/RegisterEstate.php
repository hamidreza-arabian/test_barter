<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterEstate extends Model
{
    use HasFactory ,SoftDeletes;

    protected $guarded = [];


    //estate
    //              ==many to many>  register estate fields
    //estate fields

    public function getCreatedAtAttribute($value){
        return strtotime($value) * 1000;
    }
    public function getUpdatedAtAttribute($value): float|int
    {
        return strtotime($value) * 1000;
    }

    public function registerEstateFields(): HasMany
    {
        return $this->hasMany(RegisterEstateField::class, 'register_estate_id', 'id');
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
