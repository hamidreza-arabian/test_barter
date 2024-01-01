<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileComment extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable=[
        'user_id', 'main_register_estate_id', 'comment'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function getCreatedAtAttribute($value){
        return strtotime($value) * 1000;
    }

}
