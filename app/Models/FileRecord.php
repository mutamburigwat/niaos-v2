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
        'related_entity_type',
        'related_entity_id',
        'file_name',
        'file_url',
        'file_type',
        'file_size',
        'uploaded_by',
    ];

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function scopeForEntity($query, string $type, string $id)
    {
        return $query->where('related_entity_type', $type)
            ->where('related_entity_id', $id);
    }
}
