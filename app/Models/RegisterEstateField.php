<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Expr\Cast\Bool_;
use function Laravel\Prompts\select;

class RegisterEstateField extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    function isCodeUnique($code)
    {

        return $this->query()
            ->where('text', $code)
            ->where('estate_field_id', EstateField::CODE)
            ->get()->isEmpty();

    }

    public function estateFields(){
        return $this->belongsTo(EstateField::class, 'estate_field_id', 'id');
    }

    function registerFieldItems(){
        return $this->hasMany(RegisterEstateItem::class, 'register_estate_field_id', 'id');
    }
}
