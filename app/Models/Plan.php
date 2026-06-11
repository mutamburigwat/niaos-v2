<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasUuids;

    protected $fillable = [
        'key', 'name', 'description', 'price_amount', 'currency',
        'billing_interval', 'is_active', 'sort_order', 'features', 'limits',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price_amount' => 'decimal:2',
            'features' => 'array',
            'limits' => 'array',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }
}
