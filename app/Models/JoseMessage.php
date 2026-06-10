<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JoseMessage extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $table = 'jose_messages';

    protected $fillable = [
        'jose_conversation_id',
        'workspace_id',
        'role',
        'content',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(JoseConversation::class, 'jose_conversation_id');
    }
}
