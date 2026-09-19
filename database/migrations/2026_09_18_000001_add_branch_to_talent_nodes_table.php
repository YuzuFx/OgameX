<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('talent_nodes', function (Blueprint $table) {
            // Vertical WoW-style layout: branch = column, tier = row within
            // that column. Null for a capstone node spanning all branches.
            $table->string('branch')->nullable()->after('archetype');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('talent_nodes', function (Blueprint $table) {
            $table->dropColumn('branch');
        });
    }
};
