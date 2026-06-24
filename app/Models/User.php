<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens; // <-- Yeh add karna zaroori hai

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuids, HasApiTokens; // <-- HasApiTokens yahan add karein

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'business_name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function productsServices(): HasMany
    {
        return $this->hasMany(ProductService::class, 'user_id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}