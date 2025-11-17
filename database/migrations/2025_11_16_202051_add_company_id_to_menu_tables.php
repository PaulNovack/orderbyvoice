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
        Schema::table('menu_categories', function (Blueprint $table) {
            $table->foreignId('company_id')->after('id')->constrained('companies')->cascadeOnDelete();
            $table->unique(['company_id', 'slug']);
            $table->index('company_id');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreignId('company_id')->after('id')->constrained('companies')->cascadeOnDelete();
            $table->index('company_id');
        });

        Schema::table('menu_sizes', function (Blueprint $table) {
            $table->foreignId('company_id')->after('id')->constrained('companies')->cascadeOnDelete();
            $table->index('company_id');
        });

        Schema::table('addon_groups', function (Blueprint $table) {
            $table->foreignId('company_id')->after('id')->constrained('companies')->cascadeOnDelete();
            $table->index('company_id');
        });
    }

    public function down(): void
    {
        Schema::table('menu_categories', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('menu_sizes', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });

        Schema::table('addon_groups', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};
