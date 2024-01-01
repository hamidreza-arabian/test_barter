<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstateFieldItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function estate_field(): BelongsTo
    {
        return $this->belongsTo(EstateField::class);
    }
}
