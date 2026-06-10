<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workspaces', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('business_name');
            $table->string('business_type')->nullable();
            $table->string('industry')->nullable();
            $table->string('country')->default('Zimbabwe');
            $table->string('city')->nullable();
            $table->text('address')->nullable();
            $table->string('base_currency')->default('USD');
            $table->string('timezone')->default('Africa/Harare');
            $table->string('subscription_plan')->default('free');
            $table->string('onboarding_status')->default('pending_wizard');
            $table->jsonb('channels_config')->default('{}');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignUuid('active_workspace_id')->nullable()->constrained('workspaces')->nullOnDelete();
        });

        Schema::create('workspace_members', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('workspace_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('role')->default('viewer');
            $table->string('status')->default('active');
            $table->foreignUuid('invited_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->timestamps();
            $table->unique(['workspace_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workspace_members');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('active_workspace_id');
        });
        Schema::dropIfExists('workspaces');
    }
};
