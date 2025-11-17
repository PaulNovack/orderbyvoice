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
        Schema::create('addon_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->foreignId('size_id')->nullable()->constrained('menu_sizes')->nullOnDelete();
            $table->decimal('price', 8, 2);
            $table->timestamps();

            $table->unique(['addon_id', 'size_id']);
            $table->index('addon_id');
            $table->index('size_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_prices');
    }
};
