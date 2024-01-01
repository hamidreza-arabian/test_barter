<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateField extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    const CODE = 1;


    public function estate_field_items(): HasMany
    {
        return $this->hasMany(EstateFieldItem::class);
    }
}
