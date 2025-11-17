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
        Schema::create('menu_item_addon', function (Blueprint $table) {
            $table->foreignId('menu_item_id')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('addon_id')->constrained('addons')->cascadeOnDelete();
            $table->primary(['menu_item_id', 'addon_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_item_addon');
    }
};
