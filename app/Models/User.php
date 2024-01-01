<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
//    protected static function booted(): void
//    {
//        Parent::boot();;
//
//        static::updating(function (User $user) {
//            dd('las');
//            logger("halooooooooo");
//        });
//    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    const ADVISER = 3;

    protected $fillable = [
        'role_id',
        'first_name',
        'last_name',
        'full_name',
        'gender',
        'phone_number',
        'code',
        'active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function scopeAdviser(Builder $query): void
    {
         $query->where('role_id', self::ADVISER);
    }
    public function comment(): HasMany
    {
        return $this->HasMany(EstateComment::class);
    }
    public function fileComment(): HasMany
    {
        return $this->HasMany(FileComment::class);
    }
}
