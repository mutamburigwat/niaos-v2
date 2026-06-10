<?php

namespace App\Models;

use App\Traits\BelongsToWorkspace;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JoseConversation extends Model
{
    use HasUuids, BelongsToWorkspace;

    protected $table = 'jose_conversations';

    protected $fillable = [
        'workspace_id',
        'title',
        'context',
        'status',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(JoseMessage::class, 'jose_conversation_id');
    }
}
