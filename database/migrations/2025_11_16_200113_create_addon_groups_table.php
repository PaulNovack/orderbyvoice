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
        Schema::create('addon_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('applies_to_category_id')->nullable()->constrained('menu_categories')->cascadeOnDelete();
            $table->foreignId('applies_to_item_id')->nullable()->constrained('menu_items')->cascadeOnDelete();
            $table->integer('min_select')->default(0);
            $table->integer('max_select')->nullable();
            $table->boolean('required')->default(false);
            $table->timestamps();

            $table->index('applies_to_category_id');
            $table->index('applies_to_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_groups');
    }
};
