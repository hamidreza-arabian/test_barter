<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory ,SoftDeletes;

    protected $guarded = [];

    public function scopeEsfahanCities(Builder $query): void
    {
         $query->where('province_id', Province::ESFAHAN);
    }
}
