<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('support_files', function (Blueprint $table) {
            $table->unsignedBigInteger('purchase_order_id')->nullable()->after('service_id')->index();
            $table->string('purchase_order_number')->nullable()->after('purchase_order_id')->index();
            $table->unsignedBigInteger('order_reference_id')->nullable()->after('purchase_order_number')->index();
            $table->string('order_reference_value')->nullable()->after('order_reference_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_files', function (Blueprint $table) {
            $table->dropIndex(['purchase_order_id']);
            $table->dropIndex(['purchase_order_number']);
            $table->dropIndex(['order_reference_id']);
            $table->dropIndex(['order_reference_value']);
            $table->dropColumn([
                'purchase_order_id',
                'purchase_order_number',
                'order_reference_id',
                'order_reference_value',
            ]);
        });
    }
};
