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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('segment_tag', 3)->default('BGM');

            $table->string('item', 16)->nullable();
            $table->string('consecutive', 64)->nullable();
            $table->string('observation', 256)->nullable();
            $table->decimal('ttcol_value', 16, 2)->nullable();
            $table->decimal('cargo_value', 16, 2)->nullable();
            $table->string('driver', 16)->nullable();
            $table->decimal('advance_payment', 16, 2)->nullable();
            $table->decimal('third_party_value', 16, 2)->nullable();
            $table->integer('manifest_number')->nullable();
            $table->integer('possitioning_issue')->nullable();
            $table->integer('supplier_invoice_number')->nullable();
            $table->integer('remittance_invoice_number')->nullable();
            $table->decimal('remittance_invoice_value', 16, 2)->nullable();
            $table->decimal('remittance_value', 16, 2)->nullable();
            $table->date('invoice_date')->nullable();
            $table->string('record_number', 64)->nullable();
            $table->boolean('payment_approval')->nullable();
            $table->integer('odp')->nullable();
            $table->decimal('odp_value', 16, 2)->nullable();


            $table->text('raw_segment');
            $table->foreignId('status_id')->constrained('statuses')->cascadeOnUpdate();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
