<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegisterWantEstateItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function estateFieldItems(){
        return $this->belongsTo(EstateFieldItem::class, 'estate_field_id', 'id');
    }
}
