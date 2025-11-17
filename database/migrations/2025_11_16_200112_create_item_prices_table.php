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
        Schema::create('item_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('menu_sizes')->nullOnDelete();
            $table->decimal('base_price', 8, 2);
            $table->timestamps();

            $table->unique(['item_id', 'size_id']);
            $table->index('item_id');
            $table->index('size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_prices');
    }
};
