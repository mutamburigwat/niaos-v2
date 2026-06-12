<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingRecord extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'customer_id',
        'retainer_id',
        'title',
        'description',
        'amount',
        'currency',
        'issue_date',
        'due_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'issue_date' => 'date',
            'due_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function retainer(): BelongsTo
    {
        return $this->belongsTo(Retainer::class);
    }
}
