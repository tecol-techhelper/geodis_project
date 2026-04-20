<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->bigIncrements('audit_id');
            $table->string('auditable_type', 64);
            $table->unsignedBigInteger('auditable_id');
            $table->enum('auditable_action', ['CREATED', 'UPDATED', 'DELETED']);
            $table->string('old_value', 2048)->nullable();
            $table->string('new_value', 2048)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('username', 64);
            $table->string('user_rol', 64);
            $table->string('ip_address', 64);
            $table->string('user_agent', 255);
            $table->timestamp('performed_at')->useCurrent();

            $table->index(['auditable_type', 'auditable_id'], 'audits_auditable_lookup_index');
            $table->index('auditable_action');
            $table->index('username');
        });

        Schema::create('session_logs', function (Blueprint $table) {
            $table->bigIncrements('session_log_id');
            $table->unsignedBigInteger('user_id');
            $table->string('username', 64);
            $table->enum('user_rol', ['admin', 'coord', 'account', 'ops_director', 'security', 'spec']);
            $table->string('ip_address', 64);
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('login_at')->useCurrent();
            $table->timestamp('logout_at')->nullable();
            $table->string('session_token', 128);

            $table->index('session_token');
        });

        Schema::create('failed_logins', function (Blueprint $table) {
            $table->bigIncrements('failed_login_id');
            $table->string('username', 64)->nullable();
            $table->string('ip_address', 64);
            $table->string('user_agent', 255);
            $table->timestamp('attempted_at')->useCurrent();
            $table->string('reason', 256)->nullable();

            $table->index('ip_address');
            $table->index('attempted_at');
            $table->index('username');
        });

        Schema::create('blocked_ips', function (Blueprint $table) {
            $table->bigIncrements('blocked_ip_id');
            $table->string('ip_address', 64);
            $table->string('user_agent', 255)->nullable();
            $table->timestamp('blocked_at')->useCurrent();
            $table->enum('ip_status', ['blocked', 'unblocked'])->default('blocked');

            $table->index('ip_address');
            $table->index('ip_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_ips');
        Schema::dropIfExists('failed_logins');
        Schema::dropIfExists('session_logs');
        Schema::dropIfExists('audits');
    }
};
