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
        Schema::create('addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('addon_group_id')->constrained('addon_groups')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('type', 50)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('addon_group_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addons');
    }
};
