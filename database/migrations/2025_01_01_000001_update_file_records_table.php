<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('file_records', function (Blueprint $table) {
            $table->string('original_filename')->nullable()->after('file_name');
            $table->string('disk')->default('r2')->after('file_url');
            $table->string('path')->nullable()->after('disk');
            $table->string('mime_type')->nullable()->after('file_type');
            $table->string('extension')->nullable()->after('mime_type');
            $table->text('notes')->nullable()->after('file_size');
            $table->json('metadata')->nullable()->after('notes');

            $table->foreignUuid('customer_id')->nullable()->constrained()->nullOnDelete()->after('workspace_id');
            $table->foreignUuid('support_request_id')->nullable()->constrained()->nullOnDelete()->after('customer_id');
            $table->foreignUuid('quotation_id')->nullable()->constrained()->nullOnDelete()->after('support_request_id');
        });
    }

    public function down(): void
    {
        Schema::table('file_records', function (Blueprint $table) {
            $table->dropColumn([
                'original_filename', 'disk', 'path', 'mime_type',
                'extension', 'notes', 'metadata',
                'customer_id', 'support_request_id', 'quotation_id',
            ]);
        });
    }
};
