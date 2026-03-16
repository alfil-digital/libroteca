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
        // First drop existing data to avoid foreign key constraints failing when dropping columns
        \Illuminate\Support\Facades\DB::table('cart_items')->truncate();
        \Illuminate\Support\Facades\DB::table('order_items')->truncate();

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropColumn('book_id');
            $table->morphs('sellable');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropColumn('book_id');
            $table->morphs('sellable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropMorphs('sellable');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropMorphs('sellable');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
        });
    }
};
