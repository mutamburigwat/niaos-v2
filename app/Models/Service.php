<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'name',
        'category',
        'description',
        'pricing_type',
        'base_price',
        'currency',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
        ];
    }

    public function retainers(): HasMany
    {
        return $this->hasMany(Retainer::class);
    }
}
