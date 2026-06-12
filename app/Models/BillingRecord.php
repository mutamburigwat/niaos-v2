<?php

namespace App\Models;

use App\Models\Payment;
use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'billing_record_id');
    }

    public function paidAmount(): float
    {
        return (float) $this->payments()->sum('amount');
    }

    public function balanceDue(): float
    {
        return max(0, (float) $this->amount - $this->paidAmount());
    }

    protected static function booted(): void
    {
        static::saved(function (BillingRecord $billingRecord) {
            if ($billingRecord->status !== 'paid') {
                return;
            }

            $exists = Payment::where('billing_record_id', $billingRecord->id)->exists();
            if ($exists) {
                return;
            }

            Payment::create([
                'workspace_id' => $billingRecord->workspace_id,
                'customer_id' => $billingRecord->customer_id,
                'billing_record_id' => $billingRecord->id,
                'amount' => $billingRecord->amount,
                'currency' => $billingRecord->currency ?? 'USD',
                'payment_date' => now(),
                'payment_method' => null,
                'reference' => 'Auto-created from paid billing record',
                'notes' => 'Created automatically when billing record was marked as paid.',
            ]);
        });
    }
}
