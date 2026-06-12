<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('retainer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('issue_date');
            $table->date('due_date')->nullable();
            $table->string('status')->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('workspace_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_records');
    }
};
