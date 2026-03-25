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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_id')->nullable()->after('status');
            $table->string('payment_status')->nullable()->after('payment_id');
            $table->string('external_reference')->nullable()->after('payment_status');
            $table->string('payment_method')->nullable()->after('external_reference');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_id', 'payment_status', 'external_reference', 'payment_method']);
        });
    }
};
