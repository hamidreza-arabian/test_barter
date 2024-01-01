<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MainRegisterEstate extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getCreatedAtAttribute($value){
        return strtotime($value) * 1000;
    }
    public function getUpdatedAtAttribute($value){
        return strtotime($value) * 1000;
    }

    public function assets()
    {
        return $this->hasMany(RegisterEstate::class);
    }

    public function mainRegisterWant(){
        return $this->hasMany(MainRegisterWant::class);
    }
    public function wantEstate(){
        return $this->hasMany(RegisterWantEstateType::class);
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id', 'id');
    }
    public function getLengthInHumanAttribute($value){
        return gmdate("i:s", $value);
    }

    public function EstateComments(){
        return $this->hasMany(EstateComment::class, 'base_estate_id', 'id');
    }
    public function BarterEstateComments(){
        return $this->hasMany(EstateComment::class, 'barter_estate_id', 'id');
    }
    public function FileComments(){
        return $this->hasMany(FileComment::class, 'main_register_estate_id', 'id');
    }

}
