<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('stage')->default('new');
            $table->decimal('estimated_value', 12, 2)->default(0);
            $table->string('priority')->default('medium');
            $table->string('source_channel')->nullable();
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('next_follow_up_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('won_at')->nullable();
            $table->timestamp('lost_at')->nullable();
            $table->string('lost_reason')->nullable();
            $table->timestamps();

            $table->index('workspace_id');
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
