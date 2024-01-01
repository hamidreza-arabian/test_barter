<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterWantEstateField extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    public function wantItems(){
        return $this->hasMany(RegisterWantEstateItem::class);
    }

    public function estateFields(){
        return $this->belongsTo(EstateField::class, 'estate_field_id', 'id');
    }

}
