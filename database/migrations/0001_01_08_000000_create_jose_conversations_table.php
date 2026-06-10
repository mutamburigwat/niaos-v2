<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jose_conversations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('context')->nullable();
            $table->string('status')->default('active');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('workspace_id');
        });

        Schema::create('jose_messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('jose_conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // 'user' or 'assistant'
            $table->text('content');
            $table->jsonb('metadata')->nullable();
            $table->timestamps();

            $table->index('jose_conversation_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jose_messages');
        Schema::dropIfExists('jose_conversations');
    }
};
