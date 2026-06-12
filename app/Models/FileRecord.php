<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileRecord extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $table = 'file_records';

    protected $fillable = [
        'workspace_id',
        'uploaded_by',
        'customer_id',
        'support_request_id',
        'quotation_id',
        'name',
        'original_filename',
        'disk',
        'path',
        'mime_type',
        'extension',
        'size_bytes',
        'url',
        'notes',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'size_bytes' => 'integer',
        ];
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supportRequest(): BelongsTo
    {
        return $this->belongsTo(SupportRequest::class);
    }

    public function quotation(): BelongsTo
    {
        return $this->belongsTo(Quotation::class);
    }

    public function scopeForEntity($query, string $type, string $id)
    {
        return $query->where('related_entity_type', $type)
            ->where('related_entity_id', $id);
    }

    public function isImage(): bool
    {
        return in_array($this->extension, ['png', 'jpg', 'jpeg', 'webp']);
    }

    public function formattedSize(): string
    {
        $bytes = $this->size_bytes ?? 0;

        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        }
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
