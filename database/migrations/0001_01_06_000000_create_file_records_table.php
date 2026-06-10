<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('file_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->string('related_entity_type')->nullable();
            $table->uuid('related_entity_id')->nullable();
            $table->string('file_name');
            $table->string('file_url');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->string('uploaded_by')->nullable();
            $table->timestamps();

            $table->index('workspace_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('file_records');
    }
};
