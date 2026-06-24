<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductService extends Model
{
    use HasUuids;

    protected $table = 'products_services';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'name',
        'price',
        'tax_percentage',
    ];

    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'tax_percentage' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function (self $model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });

        static::creating(function (self $model) {
            if (empty($model->user_id) && Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}