<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $fillable = [
        'workspace_id',
        'customer_id',
        'billing_record_id',
        'amount',
        'currency',
        'payment_date',
        'payment_method',
        'reference',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function billingRecord(): BelongsTo
    {
        return $this->belongsTo(BillingRecord::class);
    }
}
